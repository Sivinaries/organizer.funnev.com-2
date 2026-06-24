<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Act;
use App\Models\Withdraw;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class WithdrawController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $withdraws = $user->level === 'Admin'
            ? Withdraw::latest()->get()
            : $user->withdraws()->latest()->get();

        return view('withdraw', compact('withdraws'));
    }

    public function create()
    {
        return view('addwithdraw');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'no_rek' => 'required|string|max:255',
            'note' => 'required|string|max:255',
            'payment_type' => 'required|string|max:50',
            'amount' => 'required|numeric|min:0',
        ]);

        $data['user_id'] = $user->id;
        $data['status'] = 'Pending';

        $withdraw = Withdraw::create($data);

        // Act log
        Act::create([
            'user_id' => $user->id,
            'action' => 'create_withdraw',
            'description' => "User {$user->name} mengajukan withdraw sebesar Rp" . number_format($data['amount']),
        ]);
        Cache::forget('acts');

        return redirect()->route('withdraws')->with('success', 'Withdraw request created successfully.');
    }

    public function show($id, $no_rek)
    {
        $withdraw = Withdraw::findOrFail($id);
        return view('showwithdraw', compact('withdraw'));
    }

    public function approve($id)
    {
        $withdraw = Withdraw::findOrFail($id);
        $admin = Auth::user();
        $user = $withdraw->user;

        if ($user->balance < $withdraw->amount) {
            return back()->with('error', 'Saldo user tidak mencukupi.');
        }

        DB::beginTransaction();

        try {
            // Kurangi saldo user
            $user->decrement('balance', $withdraw->amount);

            // Update status withdraw
            $withdraw->update(['status' => 'Approved']);

            // Act log
            Act::create([
                'user_id' => $admin->id,
                'action' => 'approve_withdraw',
                'description' => "Admin {$admin->name} menyetujui withdraw user {$user->name} sebesar Rp" . number_format($withdraw->amount),
            ]);
            Cache::forget('acts');

            DB::commit();

            return redirect()->route('withdraws')->with('success', 'Withdraw berhasil disetujui.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyetujui withdraw.');
        }
    }
}
