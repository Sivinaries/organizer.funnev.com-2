<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

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
}
