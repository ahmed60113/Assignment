<?php

namespace Modules\Customers\Controllers;

use Modules\Customers\Models\customer;
use Illuminate\Validation\Validator;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Facades\Auth;
use Modules\BaseController;
use Spatie\Permission\Models\Role;

class GithubController extends BaseController
{
    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    public function githubSignin()
    {

        $user = Socialite::driver('github')->user();
        $githubId = customer::where('github_id', $user->id)->first();

        if ($githubId) {
            Auth::login($githubId);
            return redirect('/dashboard');
        } else {
            $role = Role::where('name','customer')->first();
            $createUser = customer::create([
                'name' => $user->name,
                'email' => $user->email,
                'github_id' => $user->id,
                'password' => encrypt('123456')
            ]);

            $createUser->assignRole($role);
            Auth::login($createUser);
            return redirect('/');
        }
    }
}