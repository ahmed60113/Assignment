<?php

namespace Modules\Products\Policies;

use Modules\Users\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
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
        if ($user->can('create-products')) {
            return true;
        } 
        
    }

    public function index(User $user)
    {
        return $user->can('browse-products');
    }

    public function delete(User $user)
    {
        return $user->can('delete-products');
    }

    public function restore(User $user)
    {
        return $user->can('restore-products');
    }

    public function show(User $user)
    {
        return $user->can('show-products');
    }

    public function edit(User $user)
    {
        return $user->can('edit-products');
    }

}
