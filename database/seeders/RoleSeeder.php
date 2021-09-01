<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Modules\Users\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $Admin = Role::create([
            'name' => 'admin',
            'guard_name' => 'admin'
        ]);

        Role::create([
            'name' => 'customer',
            'guard_name' => 'customer'
        ]);


        Role::create([
            'name' => 'super-admin',
            'guard_name' => 'admin'
        ]);

        $adminUser = User::create([
            'name' => 'Admin',
            'email' => 'Admin@gmail.com',
            'password' => Hash::make('123456')
        ]);
        $adminUser->assignRole($Admin);
    }
}
