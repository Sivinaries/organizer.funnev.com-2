<?php

namespace App\Http\Controllers;

use App\Models\Approval;
use Illuminate\Support\Facades\Auth;

class SubmissionController extends Controller
{
     public function index()
    {
        $user = Auth::user();

        $submissions = Approval::where('user_id', $user->id)
        ->latest()
        ->get();

        return view('submission', compact('submissions'));
    }

    public function show($id)
    {
        $submission = Approval::findOrFail($id);

        return view('showsubmission', compact('submission'));
    }

    public function destroy($id)
    {
        $submission = Approval::findOrFail($id);

        if ($submission->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $submission->delete();

        return redirect()->route('submissions')->with('success', 'Submission removed successfully!');
    }
}
