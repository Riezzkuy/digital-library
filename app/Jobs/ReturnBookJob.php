<?php

namespace App\Jobs;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReturnBookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $loan;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Loan $loan)
    {
        $this->loan = $loan;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->loan->update(['returned_at' => now()]);
        $copy = $this->loan->copy;
        $copy->update(['is_borrowed' => false]);

        // Check waiting list
        $nextLoan = Loan::where('copy_id', $copy->id)
            ->whereNull('loaned_at')
            ->orderBy('created_at')
            ->first();

        if ($nextLoan) {
            $nextLoan->update(['loaned_at' => now()]);
            $copy->update(['is_borrowed' => true]);

            ReturnBookJob::dispatch($nextLoan)->delay(now()->addMinutes(1));
        }
    }
}
