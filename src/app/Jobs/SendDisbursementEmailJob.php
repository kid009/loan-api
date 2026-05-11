<?php

namespace App\Jobs;

use App\Mail\DisbursementSuccessMail;
use App\Models\LoanTransaction;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendDisbursementEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // จำนวนครั้งที่จะพยายามส่งใหม่ถ้าล้มเหลว
    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public User $user,
        public LoanTransaction $transaction
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // คำสั่งส่งอีเมลจริงๆ จะเกิดขึ้นที่นี่
        Mail::to($this->user->email)->send(
            new DisbursementSuccessMail($this->transaction)
        );
    }
}
