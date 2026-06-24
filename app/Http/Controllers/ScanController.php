<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\TickQr;
use Illuminate\Http\Request;

class ScanController extends Controller
{

    public function scanner()
    {
        return view('scanner');
    }

    public function scan($no_ticket)
    {
        $tickQr = TickQr::with('transaction', 'transaction.event')
            ->where('no_ticket', $no_ticket)
            ->first();

        if (!$tickQr) {
            return redirect()->back()->with('error', 'Ticket not found');
        }

        return view('getticket', compact('tickQr'));
    }
}
