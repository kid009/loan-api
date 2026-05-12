<?php

namespace App\Services;

use Exception;
use App\Models\Loan;
use App\Models\LoanTransaction;
use Illuminate\Support\Facades\DB;
use App\DTOs\DisburseLoanDTO;
use App\Jobs\SendDisbursementEmailJob;

class LoanDisbursementService
{
  public function handle(DisburseLoanDTO $dto)
  {
    // 🛡️ ปราการด่านที่ 1: Idempotency Check (ป้องกันบิลซ้ำ)
    // ถ้าเลขบิลนี้เคยทำรายการไปแล้ว ให้หยุดทันที
    $existingTx = LoanTransaction::where('reference_no', $dto->reference_no)->first();

    if ($existingTx) {
      // คืนค่ารายการเดิมกลับไปเลย ไม่ต้องหักเงินใหม่
      throw new Exception("หมายเลขอ้างอิงนี้ (Ref No) ถูกใช้งานไปแล้ว");
    }

    // 🛡️ ปราการด่านที่ 2: Database Transaction & Lock (ป้องกัน Race Condition)
    return DB::transaction(function () use ($dto) {
      // ดึงข้อมูลสัญญามาและ "ล็อค" แถวนี้ไว้ (ใครเข้ามาพร้อมกันต้องรอคิว)
      $loan = Loan::where('id', $dto->loan_id)->lockForUpdate()->first();

      if (!$loan) {
        throw new Exception("ไม่พบข้อมูลวงเงินสินเชื่อ");
      }

      // เช็คว่าเงินพอไหม
      if ($loan->balance < $dto->amount) {
        throw new Exception("วงเงินคงเหลือไม่เพียงพอ");
      }

      // หักเงินคงเหลือ
      $loan->balance -= $dto->amount;

      // ถ้าเงินหมดโควต้าแล้ว ให้เปลี่ยนสถานะ
      if ($loan->balance == 0) {
        $loan->status = 'fully_disbursed';
      }

      $loan->save();

      // บันทึกประวัติการเบิก
      $transaction = LoanTransaction::create([
        'loan_id'      => $loan->id,
        'amount'       => $dto->amount,
        'reference_no' => $dto->reference_no,
        'status'       => 'success',
      ]);

      SendDisbursementEmailJob::dispatch($loan->user, $transaction);

      return $transaction;
    });
  }
}
