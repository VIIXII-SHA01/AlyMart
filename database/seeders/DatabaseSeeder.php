<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Database\Seeders\InventoryMovementSeeder;
use Database\Seeders\SaleSeeder;
use Database\Seeders\NotificationSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create users with different roles
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@alymart.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Cashier User',
            'email' => 'cashier@alymart.com',
            'password' => Hash::make('password'),
            'role' => 'cashier',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Inventory Staff',
            'email' => 'inventory@alymart.com',
            'password' => Hash::make('password'),
            'role' => 'inventory_staff',
            'is_active' => true,
        ]);

        // Create categories
        $categories = [
            ['name' => 'Beverages', 'description' => 'Soft drinks, juices, water', 'color' => '#3498db'],
            ['name' => 'Snacks', 'description' => 'Chips, cookies, candies', 'color' => '#e74c3c'],
            ['name' => 'Personal Care', 'description' => 'Soap, shampoo, toothpaste', 'color' => '#2ecc71'],
            ['name' => 'Household', 'description' => 'Cleaning supplies, paper products', 'color' => '#f39c12'],
            ['name' => 'Dairy', 'description' => 'Milk, cheese, yogurt', 'color' => '#9b59b6'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create sample products
        $products = [
            ['name' => 'Coca-Cola 1.5L', 'sku' => 'CCO1500', 'category_id' => 1, 'unit' => 'bottle', 'price' => 45.00, 'cost_price' => 35.00, 'quantity' => 50, 'min_stock_level' => 10],
            ['name' => 'Lays Classic 45g', 'sku' => 'LAY045', 'category_id' => 2, 'unit' => 'pack', 'price' => 25.00, 'cost_price' => 18.00, 'quantity' => 100, 'min_stock_level' => 20],
            ['name' => 'Safeguard Soap 115g', 'sku' => 'SGF115', 'category_id' => 3, 'unit' => 'piece', 'price' => 18.00, 'cost_price' => 12.00, 'quantity' => 30, 'min_stock_level' => 15],
            ['name' => 'Happy Tissue 200s', 'sku' => 'HTP200', 'category_id' => 4, 'unit' => 'pack', 'price' => 35.00, 'cost_price' => 25.00, 'quantity' => 25, 'min_stock_level' => 10],
            ['name' => 'Bear Brand Milk 1L', 'sku' => 'BBM1000', 'category_id' => 5, 'unit' => 'carton', 'price' => 65.00, 'cost_price' => 50.00, 'quantity' => 20, 'min_stock_level' => 8],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
        
        // Call the individual seeders
        $this->call([
            InventoryMovementSeeder::class,
            SaleSeeder::class,
            NotificationSeeder::class,
        ]);
    }
}
