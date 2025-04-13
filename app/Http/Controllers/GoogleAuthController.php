<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;


class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callbackGoogle()
    {
        try {
            $google_user = Socialite::driver('google')->stateless()->user();

            // Check if user with this email already exists
            $user = User::where('email', $google_user->getEmail())->first();

            if ($user) {
                // If google_id is not yet linked, update it
                if (!$user->google_id) {
                    $user->google_id = $google_user->getId();
                    $user->save();
                }
            } else {
                // If no user with this email, create a new one
                $user = User::create([
                    'name' => $google_user->getName(),
                    'email' => $google_user->getEmail(),
                    'google_id' => $google_user->getId(),
                ]);
            }

            Auth::login($user);
            return redirect('/');
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
