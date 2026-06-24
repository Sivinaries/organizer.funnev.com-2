<?php

namespace App\Jobs;

use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CheckExpiredTransactions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected ?int $userId;

    public function __construct(?int $userId = null)
    {
        $this->userId = $userId;
    }

    public function handle(): void
    {
        $query = Transaction::query()
            ->where('status', 'pending')
            ->where('expired_at', '<', now())
            ->whereNull('midtrans_synced_at')
            ->with('tickets');

        if ($this->userId !== null) {
            $query->where('user_id', $this->userId);
        }

        $expiredTransactions = $query->get();

        foreach ($expiredTransactions as $transaction) {
            Log::info("Job: Restoring expired transaction {$transaction->no_order}");

            foreach ($transaction->tickets as $ticket) {
                $quantity = $ticket->pivot->qty ?? 0;
                if ($quantity > 0) {
                    $ticket->increment('pcs', $quantity);
                    Log::info("Job: Restored {$quantity} pcs to ticket ID {$ticket->id}");
                }
            }

            $transaction->update([
                'status' => 'expire',
            ]);
        }
    }
}
