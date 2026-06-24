<?php

namespace App\Console\Commands;

use App\Jobs\OutEvent;
use Illuminate\Console\Command;

class OutAllEvent extends Command
{
    protected $signature = 'app:out-event';

    protected $description = 'Check and update outdated events';

    public function handle()
    {
        $this->info('Dispatching job to check outdated events...');
        OutEvent::dispatch(); // null = semua user
        $this->info('Job dispatched.');
    }
}
