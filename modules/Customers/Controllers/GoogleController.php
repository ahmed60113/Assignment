<?php

namespace Modules\Customers\Controllers;

use Modules\Customers\Models\customer;
use Illuminate\Validation\Validator;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Facades\Auth;
use Modules\BaseController;

class GoogleController extends BaseController
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleSignin()
    {

        $user = Socialite::driver('google')->user();
        $googleId = customer::where('google_id', $user->id)->first();

        if ($googleId) {
            Auth::login($googleId);
            return redirect('/dashboard');
        } else {
            $createUser = customer::create([
                'name' => $user->name,
                'email' => $user->email,
                'google_id' => $user->id,
                'password' => encrypt('john123')
            ]);

            Auth::login($createUser);
            return redirect('/');
        }
    }
}