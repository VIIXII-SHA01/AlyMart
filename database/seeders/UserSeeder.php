<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Users;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       Users::create([
        'first_name' => 'Admin',
        'last_name' => 'Example',
        'email' => 'admin@example.com',
        'password' => bcrypt('admin123'),
        'role' => 'admin',
       ]);
       Users::create([
        'first_name' => 'Cashier',
        'last_name' => 'User',
        'email' => 'cashier@example.com',
        'password' => bcrypt('cashier123'),
        'role' => 'cashier',
       ]);
        Users::create([
        'first_name' => 'Inventory',
        'last_name' => 'User',
        'email' => 'inventory@example.com',
        'password' => bcrypt('inventory123'),
        'role' => 'inventory_staff',
       ]);
    }
}
