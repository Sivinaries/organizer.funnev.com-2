<?php

namespace App\Http\Controllers;

use App\Models\Act;
use App\Models\BalanceEntry;
use App\Models\User;
use App\Models\Withdraw;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class WithdrawController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $query = Withdraw::query();

        if ($user->level !== 'Admin') {
            $query->where('user_id', $user->id);
        }

        $withdraws = $query->latest()->get();

        return view('withdraw', compact('withdraws'));
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
        $admin = Auth::user();

        try {
            $result = DB::transaction(function () use ($id) {
                // kunci baris agar tak ada balapan
                $withdraw = Withdraw::lockForUpdate()->findOrFail($id);

                // jangan proses dua kali
                if ($withdraw->status === 'Approved') {
                    return ['ok' => false, 'msg' => 'Withdraw sudah disetujui sebelumnya.'];
                }

                $user = User::lockForUpdate()->findOrFail($withdraw->user_id);

                if ($user->balance < $withdraw->amount) {
                    return ['ok' => false, 'msg' => 'Saldo user tidak mencukupi.'];
                }

                // debit ke ledger; unique(type,ref) cegah double-debit
                BalanceEntry::create([
                    'user_id' => $user->id,
                    'amount' => -1 * $withdraw->amount,
                    'type' => 'withdrawal',
                    'reference_type' => 'withdraw',
                    'reference_id' => $withdraw->id,
                    'description' => 'Penarikan disetujui Rp' . number_format($withdraw->amount),
                ]);

                // update saldo cache
                $user->decrement('balance', $withdraw->amount);

                $withdraw->status = 'Approved';
                $withdraw->settled_at = now();
                $withdraw->save();

                return ['ok' => true, 'user' => $user, 'withdraw' => $withdraw];
            });

            if (! $result['ok']) {
                return back()->with('error', $result['msg']);
            }

            Act::create([
                'user_id' => $admin->id,
                'action' => 'approve_withdraw',
                'description' => "Admin {$admin->name} menyetujui withdraw user {$result['user']->name} sebesar Rp" . number_format($result['withdraw']->amount),
            ]);
            Cache::forget('acts');

            return redirect()->route('withdraws')->with('success', 'Withdraw berhasil disetujui.');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', 'Withdraw sudah diproses atau terjadi kesalahan.');
        } catch (Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menyetujui withdraw.');
        }
    }
}
