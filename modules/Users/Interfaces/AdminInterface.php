<?php

namespace Modules\Users\Interfaces;

use Modules\Users\Models\User;
use Modules\Users\Requests\AdminRequest;
use Illuminate\Http\Request;

interface AdminInterface
{
    public function index ();

    public function delete (User $admin);

    public function restore (User $admin);

    public function login(AdminRequest $request);

    public function profile(Request $request);

    public function updateProfile(AdminRequest $request);

    public function create(AdminRequest $request);

    public function logout (Request $request);

    public function show (User $admin);

    public function edit(AdminRequest $request, User $admin);
}