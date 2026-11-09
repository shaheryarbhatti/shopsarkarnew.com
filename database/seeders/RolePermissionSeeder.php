<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create all permissions from config/acl.php
        foreach (acl_permissions() as $key) {
            Permission::firstOrCreate(['name' => $key, 'guard_name' => 'web']);
        }

        // Roles
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $admin      = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $customer   = Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);
        $contributor = Role::firstOrCreate(['name' => 'contributor', 'guard_name' => 'web']);

        // super-admin: all (enforced via Gate::before too)
        $superAdmin->syncPermissions(Permission::all());

        // admin: everything except role management by default
        $admin->syncPermissions(
            collect(acl_permissions())->reject(fn ($p) => $p === 'roles.manage')->all()
        );

        // customer & contributor get no admin permissions (front-end only)
        $customer->syncPermissions([]);
        $contributor->syncPermissions([]);

        // Default super admin account
        $superUser = User::firstOrCreate(
            ['email' => 'admin@shop.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'phone' => '03000000000',
                'is_active' => true,
            ]
        );
        if (! $superUser->hasRole('super-admin')) {
            $superUser->assignRole('super-admin');
        }
    }
}
