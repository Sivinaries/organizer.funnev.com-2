<?php

namespace App\Http\Controllers;

use App\Models\Act;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'level' => 'Organizer', // Set default level directly during creation
        ]);

        Cache::forget('organizers');

        return redirect()->route('signin')->with('toast_success', 'Registration successful! Please log in.');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return redirect()->route('signin')->with('toast_error', 'Unauthorized');
        }

        $user = Auth::user();

        if (!in_array($user->level, ['Admin', 'Organizer'])) {
            Auth::logout();
            return redirect()->back()->with('toast_error', 'Access Denied');
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $request->session()->put('auth_token', $token);

        // Audit trail: login activity
        Act::create([
            'user_id' => $user->id,
            'action' => 'login',
            'description' => "User {$user->name} logged in.",
        ]);

        Cache::forget('acts'); // ← tambahkan ini setelah create act

        return redirect()->route('dashboard')->with('toast_success', 'Login Successful!');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $user->tokens()->delete(); // Delete all tokens

            // Audit trail
            Act::create([
                'user_id' => $user->id,
                'action' => 'logout',
                'description' => "User {$user->name} logged out.",
            ]);

            Cache::forget('acts');
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('signin')->with('toast_success', 'Logged Out Successful!');
    }
}
