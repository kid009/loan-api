<?php

namespace Tests\Feature;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class DisbursementApiTest extends TestCase
{
    // เรียกใช้งาน trait นี้เพื่อรีเซ็ต Database ทุกครั้งที่รันเทสต์
    use RefreshDatabase;

    // เทสต์เคสที่ 1: การเบิกเงินสำเร็จ (Happy Path)
    public function test_user_can_disburse_loan_successfully(): void
    {
        // 1. Arrange (เตรียมการ): สร้างลูกค้าและวงเงินจำลอง 10,000 บาท
        $user = User::factory()->create();

        $loan = Loan::create([
            'user_id'      => $user->id,
            'total_amount' => 10000.00,
            'balance'      => 10000.00,
            'status'       => 'active'
        ]);

        // 2. Act (ลงมือทำ): จำลองการยิง API ขอเบิกเงิน 3,000 บาท
        $payload = [
            'loan_id'      => $loan->id,
            'amount'       => 3000.00,
            'reference_no' => 'REF-001'
        ];

        // จำลองการยิง HTTP POST ไปที่ Route ของเรา
        $response = $this->postJson('/api/v1/disbursements', $payload);

        // // $response->dump();
        // 3. Assert (ตรวจสอบ): เช็คผลลัพธ์ว่าถูกต้องตามที่คาดหวังไหม

        // 3.1 ตรวจสอบว่า API ตอบกลับสถานะ 200 (OK)
        $response->assertStatus(200);

        // 3.2 ตรวจสอบว่าเงินในฐานข้อมูลถูกหักเหลือ 7,000 จริงไหม (10,000 - 3,000)
        $this->assertDatabaseHas('loans', [
            'id'      => $loan->id,
            'balance' => 7000.00,
        ]);

        // 3.3 ตรวจสอบว่ามีการบันทึกประวัติลงตาราง loan_transactions จริงไหม
        $this->assertDatabaseHas('loan_transactions', [
            'loan_id'      => $loan->id,
            'amount'       => 3000.00,
            'reference_no' => 'REF-001',
            'status'       => 'success'
        ]);
    }
}
