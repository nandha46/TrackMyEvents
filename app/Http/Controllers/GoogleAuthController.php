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
    public function callback (): RedirectResponse
    {
        try{
            $user = Socialite::driver('google')->user();
        } catch(Throwable $e){
            dd($e);
            return redirect('/')->with('error', 'Google authentication failed.');
        }

        $existingUser = User::where('email', $user->getEmail())->first();

        if($existingUser){
            Auth::login($user);
        } else {
            $newUser = User::create([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => Hash::make(Str::random(16)),
                'email_verified_at' => now(),
            ]);

            $file_resource = fopen($user->getAvatar(), 'r');


            Storage::disk('local')->putFile('google avatars', $file_resource);

            Auth::login($newUser);
        }

        return redirect('/dashboard');
    }
}
