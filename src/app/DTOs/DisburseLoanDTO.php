<?php

namespace App\DTOs;

use App\Http\Requests\DisburseLoanRequest;

readonly class DisburseLoanDTO
{
  public function __construct(
    public string $loan_id,
    public float $amount,
    public string $reference_no
  ) {}

  // Method พิเศษสำหรับแปลง Form Request ให้กลายเป็น DTO
  public static function fromRequest(DisburseLoanRequest $request): self
  {
    return new self(
      loan_id: $request->input('loan_id'),
      amount: $request->input('amount'),
      reference_no: $request->input('reference_no')
    );
  }
}
