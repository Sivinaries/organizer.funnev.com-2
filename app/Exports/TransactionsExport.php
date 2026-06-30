<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TransactionsExport implements FromView, ShouldAutoSize
{
    public $transactions;
    public $start;
    public $end;

    public function __construct($transactions, $start = null, $end = null)
    {
        $this->transactions = $transactions;
        $this->start = $start;
        $this->end = $end;
    }

    public function view(): View
    {
        return view('exports.transactionReport', [
            'transactions' => $this->transactions,
            'start' => $this->start,
            'end' => $this->end,
        ]);
    }
}
