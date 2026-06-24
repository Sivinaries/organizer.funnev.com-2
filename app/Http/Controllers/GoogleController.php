<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Act;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = User::where('google_id', $googleUser->id)->first();

            if (!$user) {
                $user = User::where('email', $googleUser->email)->first();

                if ($user) {
                    $user->update([
                        'google_id' => $googleUser->id
                    ]);
                } else {
                    $user = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'password' => Hash::make('123456dummy'),
                        'level' => 'User',
                    ]);

                    Act::create([
                        'user_id' => $user->id,
                        'action' => 'register_google',
                        'description' => "User {$user->name} mendaftar menggunakan Google.",
                    ]);
                    Cache::forget('acts');
                }

                Cache::forget('users');
            }

            Auth::login($user);

            Act::create([
                'user_id' => $user->id,
                'action' => 'login_google',
                'description' => "User {$user->name} login menggunakan Google.",
            ]);
            Cache::forget('acts');

            $token = $user->createToken('auth_token')->plainTextToken;

            return redirect('https://funnev.com/login?token=' . $token);
        } catch (Exception $e) {
            Log::error('Google login failed: ' . $e->getMessage());
            return redirect('https://funnev.com/login?error=google_auth_failed');
        }
    }
}
