<?php

namespace App\Http\Controllers;

use App\Models\Act;
use App\Models\Hot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class HotController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->level !== 'Admin') {
            abort(403, 'Unauthorized');
        }

        $hots = Cache::remember('hots', now()->addMinutes(30), function () {
            return Hot::with('event')->latest()->get();
        });

        return view('hot', compact('hots'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'event_id' => 'required|exists:events,id',
        ]);

        $hot = Hot::create($data);

        $this->logActivity(
            'Hot Registered',
            'Event "' . $hot->event->name . '" has been registered as hot',
            auth()->id()
        );

        $this->clearHotCache();

        return redirect()->route('hots')->with('success', 'Hot successfully registered!');
    }

    public function destroy($id)
    {
        $hot = Hot::with('event')->findOrFail($id);
        $eventName = $hot->event->name;

        $hot->delete();

        $this->logActivity(
            'Hot Removed',
            'Hot event "' . $eventName . '" has been removed',
            auth()->id()
        );

        $this->clearHotCache();

        return redirect()->route('hots')->with('success', 'Hot removed!');
    }

    private function clearHotCache()
    {
        Cache::forget('hots');
        Cache::forget('hots_user');
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
