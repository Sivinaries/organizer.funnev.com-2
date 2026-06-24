<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Approval;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class ApprovalController extends Controller
{
    public function index()
    {
        $approvals = Approval::all();

        return view('approval', compact('approvals'));
    }

    public function store($id)
    {
        DB::transaction(function () use ($id) {
            $approval = Approval::findOrFail($id); // Ambil model berdasarkan ID

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

            $approval->delete(); // Hapus setelah di-approve
        });

        return redirect(route('approvals'))->with('success', 'Event successfully posted!');
    }

    public function show($id, $name)
    {
        $approval = Approval::findOrFail($id);

        return view('showapproval', compact('approval'));
    }

    public function destroy($id)
    {
        Approval::destroy($id);

        return redirect(route('approvals'))->with('success', 'Approvals Removed !');
    }
}
