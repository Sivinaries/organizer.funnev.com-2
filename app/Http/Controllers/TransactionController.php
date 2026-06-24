<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
     public function index()
    {
        $user = Auth::user();

        $transactions = $user->level === 'Admin'
            ? Transaction::with(['user', 'tickets', 'event.user'])->latest()->get()
            : Transaction::with(['user', 'tickets', 'event.user'])
            ->whereHas('event', fn($query) => $query->where('user_id', $user->id))
            ->latest()
            ->get();

        return view('transaction', compact('transactions'));
    }

    public function show($id, $no_order)
    {
        $transaction = Transaction::with(['user', 'event', 'tickets', 'attendances'])->findOrFail($id);

        return view('showtransaction', compact('transaction'));
    }
}
