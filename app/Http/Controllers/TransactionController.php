<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Exports\TransactionsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $query = Transaction::query()->with(['user', 'tickets', 'event.user']);

        if ($user->level !== 'Admin') {
            $query->whereHas('event', fn($q) => $q->where('user_id', $user->id));
        }

        $transactions = $query->latest()->get();

        return view('transaction', compact('transactions'));
    }

    public function show($id, $no_order)
    {
        $transaction = Transaction::with(['user', 'event', 'tickets', 'attendances'])->findOrFail($id);

        return view('showtransaction', compact('transaction'));
    }

        public function export(Request $request)
    {
        $user = Auth::user();
        $start = $request->query('start');
        $end = $request->query('end');

        $query = Transaction::with(['user', 'tickets', 'event'])->latest();

        if ($user->level !== 'Admin') {
            $query->whereHas('event', fn($q) => $q->where('user_id', $user->id));
        }
        if ($start) {
            $query->whereDate('created_at', '>=', $start);
        }
        if ($end) {
            $query->whereDate('created_at', '<=', $end);
        }

        $transactions = $query->get();

        $filename = 'transactions_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new TransactionsExport($transactions, $start, $end), $filename);
    }

}
