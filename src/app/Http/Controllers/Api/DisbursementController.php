<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DisburseLoanRequest;
use App\DTOs\DisburseLoanDTO;
use App\Services\LoanDisbursementService;
use Illuminate\Http\JsonResponse;

class DisbursementController extends Controller
{
    // 1. ใช้ Dependency Injection เพื่อเรียกใช้งาน Service
    public function __construct(
        private readonly LoanDisbursementService $disbursementService
    ) {}

    // 2. เปลี่ยน Type Hint ของ Request เป็น DisburseLoanRequest (รปภ. ที่เราสร้างไว้)
    public function __invoke(DisburseLoanRequest $request): JsonResponse
    {
        try {
            // 3. นำข้อมูลที่ผ่านการ Validate แล้วมาแพ็กใส่กล่อง DTO
            $dto = DisburseLoanDTO::fromRequest($request);

            // 4. โยนกล่อง DTO ให้ Service ไปจัดการหักเงินและส่งอีเมล
            $transaction = $this->disbursementService->handle($dto);

            // 5. ส่งผลลัพธ์กลับเป็น JSON
            return response()->json([
                'status'  => 'success',
                'message' => 'Disbursement processed successfully.',
                'data'    => [
                    'transaction_id' => $transaction->id,
                    'reference_no'   => $transaction->reference_no,
                    'amount'         => $transaction->amount,
                ]
            ], 200);
        } catch (\Exception $e) {
            // 🚨 4. ถ้า Service โยน Exception ออกมา ให้ดักไว้ตรงนี้!
            // แล้วตอบกลับเป็น 400 Bad Request พร้อมข้อความที่เราตั้งไว้
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(), // ดึงข้อความ "หมายเลขอ้างอิงนี้ถูกใช้งานไปแล้ว" มาโชว์
            ], 400);
        }
    }
}
