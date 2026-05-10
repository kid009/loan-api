<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\Loan;

class LoanTransaction extends Model
{
    use HasUuids;

    protected $fillable = [
        'loan_id',
        'amount',
        'reference_no',
        'status'
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
