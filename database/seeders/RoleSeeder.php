<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin  = Role::create(['name' => 'admin']);
        $user   = Role::create(['name' => 'user']);

        $permission = [
            Permission::create(['name' => 'permission-index']),
            Permission::create(['name' => 'permission-create']),
            Permission::create(['name' => 'permission-edit']),
            Permission::create(['name' => 'permission-destroy']),
            Permission::create(['name' => 'role-index']),
            Permission::create(['name' => 'role-create']),
            Permission::create(['name' => 'role-edit']),
            Permission::create(['name' => 'role-destroy']),
            Permission::create(['name' => 'role-access']),
            Permission::create(['name' => 'user-index']),
            Permission::create(['name' => 'user-create']),
            Permission::create(['name' => 'user-edit']),
            Permission::create(['name' => 'user-destroy']),
            Permission::create(['name' => 'user-password']),
        ]; 

        $admin->givePermissionTo($permission);
    }
}
