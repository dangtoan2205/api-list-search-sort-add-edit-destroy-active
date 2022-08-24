<?php

namespace Modules\Admin\Seeders;

use Illuminate\Database\Seeder;
use Modules\Admin\Models\Admin;
use Modules\Admin\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $actions = ['index', 'store', 'show', 'update', 'destroy'];

        foreach ($actions as $key) {
            Permission::updateOrCreate(['name' => 'admin.' . $key, 'guard_name' => Role::GUARD_NAME_ADMIN]);
        }

        $admin = Admin::updateOrCreate(
            [
                'email' => 'admin@admin.com',
            ],
            [
                'email' => 'admin@admin.com',
                'username' => 'admin',
                'is_active' => 1,
                'password' => bcrypt('123123'),
            ]
        );


        // $admin->assignRole(config('constant.role_admin'));
    }
}
