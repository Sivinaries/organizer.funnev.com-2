<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PagesController extends Controller
{

    public function signin()
    {
        return view('signin');
    }

    public function signup()
    {
        return view('signup');
    }

    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $userId = Auth::id();
        $year = $request->input('selectedYear', date('Y'));

        // Kalau admin, ambil 1 event acak untuk dashboard (supaya tidak null)
        if (strtolower($user->level) === 'admin') {
            $event = Event::first(); // bisa null jika tidak ada event
        } else {
            // Organizer: ambil event miliknya
            $event = $user->events()->latest()->first();
        }

        $eventId = $event?->id;

        // Events Chart - Jumlah event per bulan
        if (strtolower($user->level) === 'admin') {
            $events = Event::selectRaw("COUNT(*) as count, DATE_FORMAT(created_at, '%M') as month_name, MONTH(created_at) as month_number")
                ->whereYear('created_at', $year)
                ->groupBy('month_number', 'month_name')
                ->orderBy('month_number')
                ->pluck('count', 'month_name');
        } else {
            $events = Event::selectRaw("COUNT(*) as count, DATE_FORMAT(created_at, '%M') as month_name, MONTH(created_at) as month_number")
                ->whereYear('created_at', $year)
                ->where('user_id', $user->id)
                ->groupBy('month_number', 'month_name')
                ->orderBy('month_number')
                ->pluck('count', 'month_name');
        }

        $labels1 = $events->keys();
        $data1 = $events->values();

        $labels2 = collect();
        $data2 = collect();
        $labels3 = collect();
        $data3 = collect();
        $labels4 = collect(); // <- inisialisasi agar tidak undefined
        $data4 = collect();   // <- inisialisasi agar tidak undefined

        if ($eventId) {
            // Tickets Chart
            $soldTickets = DB::table('trans_ticks')
                ->join('transactions', 'trans_ticks.transaction_id', '=', 'transactions.id')
                ->join('tickets', 'trans_ticks.ticket_id', '=', 'tickets.id')
                ->selectRaw("SUM(trans_ticks.qty) as total_sold, DATE_FORMAT(transactions.created_at, '%M') as month_name, MONTH(transactions.created_at) as month_number")
                ->whereYear('transactions.created_at', $year)
                ->where('tickets.event_id', $eventId)
                ->where('transactions.status', 'settlement') // opsional, kalau mau hanya hitung yang sudah dibayar
                ->groupBy('month_number', 'month_name')
                ->orderBy('month_number')
                ->pluck('total_sold', 'month_name');

            $labels2 = $soldTickets->keys();
            $data2 = $soldTickets->values();

            // Transactions Chart
            $transactions = Transaction::selectRaw("SUM(net_income) as total_amount, DATE_FORMAT(created_at, '%M') as month_name, MONTH(created_at) as month_number")
                ->whereYear('created_at', $year)
                ->where('event_id', $eventId)
                ->where('status', 'settlement')   // <-- filter by status settlement
                ->groupBy('month_number', 'month_name')
                ->orderBy('month_number')
                ->pluck('total_amount', 'month_name');

            $labels3 = $transactions->keys();
            $data3 = $transactions->values();

            // Balance Chart
            $balances = User::selectRaw("SUM(balance) as total_amount, DATE_FORMAT(created_at, '%M') as month_name, MONTH(created_at) as month_number")
                ->where('id', $userId) // filter by authenticated user
                ->whereYear('created_at', $year)
                ->groupBy('month_number', 'month_name')
                ->orderBy('month_number')
                ->pluck('total_amount', 'month_name');

            $labels4 = $balances->keys();
            $data4 = $balances->values();
        }

        return view('dashboard', [
            'labels1' => $labels1,
            'data1' => $data1,
            'labels2' => $labels2,
            'data2' => $data2,
            'labels3' => $labels3,
            'data3' => $data3,
            'labels4' => $labels4,
            'data4' => $data4,
            'selectedYear' => $year,
        ]);
    }
    
    public function search(Request $request)
    {
        $searchTerm = $request->input('search');
        $user = Auth::user();

        $transactionsQuery = Transaction::with('user', 'tickets', 'event');

        // If the user is not Admin, restrict results to events they own
        if ($user->level !== 'Admin') {
            $transactionsQuery->whereHas('event', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        // Apply search filters
        if (!empty($searchTerm)) {
            $transactionsQuery->where(function ($query) use ($searchTerm) {
                $query->where('no_order', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('event', function ($q) use ($searchTerm) {
                        $q->where('name', 'like', '%' . $searchTerm . '%');
                    })
                    ->orWhereHas('user', function ($q) use ($searchTerm) {
                        $q->where('name', 'like', '%' . $searchTerm . '%');
                    });
            });
        }

        $transactions = $transactionsQuery->get();

        return view('search', compact('transactions'));
    }

    public function profil()
    {
        return view('profil');
    }
}
