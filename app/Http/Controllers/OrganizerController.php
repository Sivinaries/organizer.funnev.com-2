<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class OrganizerController extends Controller
{
    public function index()
    {
        $users = Cache::remember('organizers', now()->addMinutes(60), function () {
            return User::where('level', 'Organizer')->latest()->get();
        });

        return view('organizer', compact('users'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->level !== 'Organizer') {
            return redirect()->route('organizers')->withErrors('Only organizers can be removed.');
        }

        $user->delete();

        Cache::forget('organizers');

        return redirect()->route('organizers')->with('success', 'User removed successfully!');
    }
}
