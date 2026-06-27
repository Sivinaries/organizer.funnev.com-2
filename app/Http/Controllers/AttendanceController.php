<?php

namespace App\Http\Controllers;

use App\Models\TickQr;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $query = Attendance::query()->with(['event', 'transaction', 'tickQr']);

        if ($user->level !== 'Admin') {
            $query->whereHas('event', fn($q) => $q->where('user_id', $user->id));
        }

        $attendances = $query->latest()->get();

        return view('attendance', compact('attendances'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tick_qr_id' => 'required|integer|exists:tick_qrs,id',
        ]);

        try {
            DB::beginTransaction();

            // Lock the row for update
            $tickQr = TickQr::with('transaction', 'transaction.event')
                ->where('id', $data['tick_qr_id'])
                ->lockForUpdate()
                ->first();

            if (!$tickQr) {
                DB::rollBack();
                return response()->json(['error' => 'Ticket not found.'], 404);
            }

            $alreadyScanned = Attendance::where('tick_qr_id', $tickQr->id)->exists();

            if ($alreadyScanned) {
                DB::rollBack();
                return response()->json(['error' => 'QR code already used.'], 409);
            }

            Attendance::create([
                'tick_qr_id' => $tickQr->id,
                'event_id' => $tickQr->transaction->event_id,
                'transaction_id' => $tickQr->transaction_id,
                'scanned_at' => now(),
            ]);

            $tickQr->update(['status' => 'true']);

            DB::commit();

            return redirect()->back()->with('success', 'Attendance recorded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Server error.'], 500);
        }
    }
}
