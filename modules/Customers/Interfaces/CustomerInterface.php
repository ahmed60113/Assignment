<?php

namespace Modules\Customers\Interfaces;

use Modules\Customers\Models\customer;
use Modules\Customers\Requests\CustomerRequest;
use Illuminate\Http\Request;

interface CustomerInterface
{
    public function index ();

    public function delete (Customer $customer);

    public function restore ($id);

    public function loginBymail(CustomerRequest $request);

    public function RegristerBymail(CustomerRequest $request);

    public function profile(Customer $customer);

    public function updateProfile(Customer $customer);

    public function create(CustomerRequest $request);

    public function logout (Request $request);

    public function show (Customer $customer);

    public function edit(CustomerRequest $request, Customer $customer);
}