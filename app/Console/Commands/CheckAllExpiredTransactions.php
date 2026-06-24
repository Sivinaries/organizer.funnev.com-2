<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\CheckExpiredTransactions;

class CheckAllExpiredTransactions extends Command
{
     protected $signature = 'transactions:check-expired';

    protected $description = 'Check and update expired transactions and restore tickets';

    public function handle()
    {
        $this->info('Dispatching job to check expired transactions...');
        CheckExpiredTransactions::dispatch(); // null = semua user
        $this->info('Job dispatched.');
    }
}
