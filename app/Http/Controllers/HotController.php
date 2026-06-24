<?php

namespace App\Http\Controllers;

use App\Models\Hot;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HotController extends Controller
{
    public function index()
    {
        $hots = Cache::remember('hots', now()->addMinutes(30), function () {
            return Hot::with('event')->get();
        });

        return view('hot', compact('hots'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'event_id' => 'required|exists:events,id',
        ]);

        Hot::create($data);

        $this->clearHotCache();

        return redirect()->route('hots')->with('success', 'Hot successfully registered!');
    }

    public function destroy($id)
    {
        Hot::findOrFail($id)->delete();

        $this->clearHotCache();

        return redirect()->route('hots')->with('success', 'Hot removed!');
    }

    private function clearHotCache()
    {
        Cache::forget('hots');
        Cache::forget('hots_user');
    }
}
