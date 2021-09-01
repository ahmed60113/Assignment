<?php

namespace Modules\Customers\Controllers;

use Modules\Customers\Models\customer;
use Illuminate\Validation\Validator;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Facades\Auth;
use Modules\BaseController;
use Spatie\Permission\Models\Role;

class FbController extends BaseController
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function facebookSignin()
    {
        $user = Socialite::driver('facebook')->user();
        $facebookId = customer::where('facebook_id', $user->id)->first();

        if ($facebookId) {
            Auth::login($facebookId);
            return redirect('/dashboard');
        } else {
            $role = Role::where('name','customer')->first();
            $createUser = customer::create([
                'name' => $user->name,
                'email' => $user->email,
                'facebook_id' => $user->id,
                'password' => encrypt('john123')
            ]);

            $createUser->assignRole($role);
            Auth::login($createUser);
            return redirect('/');
        }
    }
}