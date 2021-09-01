<?php

namespace Modules\Customers\policies;

use Modules\Customers\Models\Customer;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
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

    public function create(Customer $customer)
    {
        if ($customer->can('create-customer')) {
            return true;
        } 
        
    }

    public function index(Customer $customer)
    {
        return $customer->can('browse-customer');
    }

    public function delete(Customer $customer)
    {
        return $customer->can('delete-customer');
    }

    public function restore(Customer $customer)
    {
        return $customer->can('restore-customer');
    }

    public function show(Customer $customer)
    {
        return $customer->can('show-customer');
    }

    public function edit(Customer $customer)
    {
        return $customer->can('edit-customer');
    }

}
