<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminPermissions = [
            'browse-admin', 'show-admin', 'create-admin', 'edit-admin', 'delete-admin',
            'restore-admin', 'browse-customer', 'delete-customer', 'restore-customer',
            'create-products', 'edit-products', 'delete-products', 'restore-products',
            'browse-order', 'show-products', 'browse-products', 'show-customer', 'create-customer', 'edit-customer'
        ];

        $customerPermissions = [
            'browse-products', 'show-products', 'create-order', 'edit-order', 'delete-order',
            'show-order'
        ];

        $role = Role::findByName('admin');
        foreach ($adminPermissions as $permission) {
            Permission::create([
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
            $role->givePermissionTo($permission);
        }


        $role = Role::find('2');
        foreach ($customerPermissions as $permission) {
            Permission::create([
                'name' => $permission,
                'guard_name' => 'customer'
            ]);
            $role->givePermissionTo($permission);
        }
    }
}
