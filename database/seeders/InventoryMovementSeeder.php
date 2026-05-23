<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use App\Models\InventoryMovement;
use Carbon\Carbon;

class InventoryMovementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user
        $admin = User::where('email', 'admin@alymart.com')->first();
        if (!$admin) {
            $this->command->error('Admin user not found. Please run DatabaseSeeder first.');
            return;
        }

        // Get some products
        $products = Product::limit(5)->get();
        
        if ($products->isEmpty()) {
            $this->command->error('No products found. Please run DatabaseSeeder first.');
            return;
        }

        // Create sample inventory movements
        $movements = [
            [
                'product' => $products[0],
                'type' => 'stock_in',
                'quantity' => 50,
                'reason' => 'Initial stock',
                'created_at' => Carbon::now()->subDays(10),
            ],
            [
                'product' => $products[1],
                'type' => 'stock_in',
                'quantity' => 30,
                'reason' => 'Initial stock',
                'created_at' => Carbon::now()->subDays(9),
            ],
            [
                'product' => $products[0],
                'type' => 'stock_out',
                'quantity' => 5,
                'reason' => 'Damaged items',
                'created_at' => Carbon::now()->subDays(5),
            ],
            [
                'product' => $products[2],
                'type' => 'stock_in',
                'quantity' => 25,
                'reason' => 'New stock delivery',
                'created_at' => Carbon::now()->subDays(3),
            ],
            [
                'product' => $products[1],
                'type' => 'adjustment',
                'quantity' => 2,
                'reason' => 'Stock count adjustment',
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'product' => $products[3],
                'type' => 'stock_in',
                'quantity' => 40,
                'reason' => 'Bulk purchase',
                'created_at' => Carbon::now()->subDays(1),
            ],
            [
                'product' => $products[0],
                'type' => 'return',
                'quantity' => 3,
                'reason' => 'Customer return',
                'created_at' => Carbon::yesterday(),
            ],
            [
                'product' => $products[4],
                'type' => 'stock_in',
                'quantity' => 15,
                'reason' => 'Reorder stock',
                'created_at' => Carbon::today(),
            ],
        ];

        foreach ($movements as $movementData) {
            $product = $movementData['product'];
            $oldQuantity = $product->quantity;
            
            // Calculate new quantity
            $newQuantity = $oldQuantity;
            switch ($movementData['type']) {
                case 'stock_in':
                case 'return':
                    $newQuantity += $movementData['quantity'];
                    break;
                case 'stock_out':
                case 'sale':
                    $newQuantity -= $movementData['quantity'];
                    break;
                case 'adjustment':
                    $newQuantity += $movementData['quantity']; // Assuming positive for demo
                    break;
            }

            // Create inventory movement
            InventoryMovement::create([
                'product_id' => $product->id,
                'user_id' => $admin->id,
                'movement_type' => $movementData['type'],
                'quantity' => $movementData['quantity'],
                'previous_quantity' => $oldQuantity,
                'new_quantity' => $newQuantity,
                'reason' => $movementData['reason'],
                'unit_cost' => $product->cost_price,
                'created_at' => $movementData['created_at'],
                'updated_at' => $movementData['created_at'],
            ]);

            // Update product quantity
            $product->quantity = $newQuantity;
            $product->save();
        }

        $this->command->info('Sample inventory movements created successfully!');
    }
}
