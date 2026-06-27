<?php

namespace App\Http\Controllers;

use App\Models\Act;
use App\Models\Approval;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SubmissionController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $submissions = Approval::query()->where('user_id', $user->id)
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

        $submissionName = $submission->name;
        
        $submission->delete();

        $this->logActivity(
            'Submission Deleted',
            'Submission "' . $submissionName . '" has been deleted',
            auth()->id()
        );

        return redirect()->route('submissions')->with('success', 'Submission removed successfully!');
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
