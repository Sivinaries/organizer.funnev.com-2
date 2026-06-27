<?php

namespace App\Http\Controllers;

use App\Models\Act;
use App\Models\Approval;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ApprovalController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->level !== 'Admin') {
            abort(403, 'Unauthorized');
        }

        $approvals = Approval::all();

        return view('approval', compact('approvals'));
    }

    public function store($id)
    {
        $user = Auth::user();

        if ($user->level !== 'Admin') {
            abort(403, 'Unauthorized');
        }

        DB::transaction(function () use ($id) {
            $approval = Approval::findOrFail($id);

            Event::create([
                'name' => $approval->name,
                'no_telpon' => $approval->no_telpon,
                'ktp' => $approval->ktp,
                'event' => $approval->event,
                'organizer' => $approval->organizer,
                'location' => $approval->location,
                'description' => $approval->description,
                'start_time' => $approval->start_time,
                'end_time' => $approval->end_time,
                'user_id' => $approval->user_id,
                'kategori_id' => $approval->kategori_id,
                'img' => $approval->img,
                'img2' => $approval->img2,
                'syarat' => $approval->syarat,
                'status' => 'Approve',
            ]);

            $this->logActivity('Event Approved', 'Event "' . $approval->name . '" has been approved and posted', $approval->user_id);

            $approval->delete();
        });

        return redirect(route('approvals'))->with('success', 'Event successfully posted!');
    }

    public function show($id, $name)
    {
        $user = Auth::user();

        if ($user->level !== 'Admin') {
            abort(403, 'Unauthorized');
        }

        $approval = Approval::findOrFail($id);

        return view('showapproval', compact('approval'));
    }

    public function destroy($id)
    {
        $user = Auth::user();

        if ($user->level !== 'Admin') {
            abort(403, 'Unauthorized');
        }

        $approval = Approval::findOrFail($id);
        $approvalName = $approval->name;
        $userId = $approval->user_id;

        Approval::destroy($id);

        $this->logActivity('Approval Rejected', 'Approval "' . $approvalName . '" has been rejected', $userId);

        return redirect(route('approvals'))->with('success', 'Approvals Removed !');
    }

    private function logActivity(string $action, string $description, int $userId): void
    {
        Act::create([
            'user_id' => $userId,
            'action' => strtolower(str_replace(' ', '_', $action)),
            'description' => $description,
        ]);

        Cache::forget('acts');
    }
}
