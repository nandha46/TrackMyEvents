<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to the Google's OAuth page.
     */
    public function redirect (): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the callback from Google.
     */
    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Google OAuth error: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Google authentication failed. Please try again.');
        }

        $existingUser = User::where('email', $googleUser->getEmail())->first();

        if ($existingUser) {
            Auth::login($existingUser);
        } else {
            $avatarName = 'default.jpg';

            if ($googleUser->getAvatar()) {
                try {
                    $avatarContents = @file_get_contents($googleUser->getAvatar());
                    if ($avatarContents) {
                        $avatarName = 'google_' . uniqid() . '.jpg';
                        file_put_contents(public_path('uploads/profile_pictures/' . $avatarName), $avatarContents);
                    }
                } catch (Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning('Google avatar download failed: ' . $e->getMessage());
                }
            }

            $newUser = User::create([
                'name' => $googleUser->getName() ?? $googleUser->getNickname() ?? 'Google User',
                'email' => $googleUser->getEmail(),
                'password' => Hash::make(Str::random(24)),
                'email_verified_at' => now(),
                'avatar' => $avatarName,
            ]);

            Auth::login($newUser);
        }

        return redirect('/dashboard');
    }
}
