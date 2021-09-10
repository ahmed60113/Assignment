<?php

namespace Modules\Orders\policies;

use Modules\Users\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function create(User $user)
    {
        if ($user->can('create-admin')) {
            return true;
        } 
        
    }

    public function index(User $user)
    {
        return $user->can('browse-order');
    }

    public function delete(User $user)
    {
        return $user->can('delete-admin');
    }

    public function restore(User $user)
    {
        return $user->can('restore-admin');
    }

    public function show(User $user)
    {
        return $user->can('show-admin');
    }

    public function edit(User $user)
    {
        return $user->can('update-admin-admin');
    }

}
