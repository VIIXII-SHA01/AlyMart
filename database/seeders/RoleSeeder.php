<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $cashierRole = Role::firstOrCreate(['name' => 'cashier']);
        $inventoryStaffRole = Role::firstOrCreate(['name' => 'inventory_staff']);

        // Create permissions (optional, you can add more as needed)
        $permissions = [
            'manage users',
            'manage products',
            'view dashboard',
            'manage inventory',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles
        $adminRole->givePermissionTo(Permission::all());
        $userRole->givePermissionTo(['view dashboard']);
        $cashierRole->givePermissionTo(['view dashboard', 'manage products']);
        $inventoryStaffRole->givePermissionTo(['view dashboard', 'manage inventory']);

        // Assign roles to existing users based on their role field
        User::where('role', 'admin')->get()->each(function ($user) use ($adminRole) {
            $user->assignRole($adminRole);
        });

        User::where('role', 'cashier')->get()->each(function ($user) use ($cashierRole) {
            $user->assignRole($cashierRole);
        });

        User::where('role', 'inventory_staff')->get()->each(function ($user) use ($inventoryStaffRole) {
            $user->assignRole($inventoryStaffRole);
        });
    }
}
