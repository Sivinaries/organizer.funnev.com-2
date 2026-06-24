<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    public function index()
    {
        $users = Cache::remember('users', now()->addMinutes(60), fn() => User::where('level', 'User')->get());

        return view('user', compact('users'));
    }

    public function destroy($id)
    {
        User::destroy($id);

        Cache::forget('users');

        return redirect()->route('users')->with('success', 'User Removed!');
    }
}
