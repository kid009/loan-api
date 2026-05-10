<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\LoanTransaction;


class Loan extends Model
{
    use HasUuids; // สั่งให้ Laravel สร้าง UUID ให้อัตโนมัติเวลา Save

    protected $fillable = [
        'user_id',
        'total_amount',
        'balance',
        'status'
    ];

    public function transactions()
    {
        return $this->hasMany(LoanTransaction::class);
    }
}
