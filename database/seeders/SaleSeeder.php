<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing sales data
        DB::table('sale_items')->delete();
        DB::table('sales')->delete();
        
        // Get some products and users
        $products = Product::where('is_active', true)->limit(10)->get();
        $cashiers = User::where('role', 'cashier')->limit(3)->get();
        
        if ($products->isEmpty() || $cashiers->isEmpty()) {
            $this->command->info('No products or cashiers found for creating sample sales.');
            return;
        }
        
        // Create sample sales for different dates
        $salesData = [
            [
                'date' => Carbon::now()->subDays(7), // 1 week ago
                'status' => 'completed',
                'customer_name' => 'Juan Dela Cruz',
                'notes' => 'Regular customer - paid in cash',
                'items_count' => 3,
            ],
            [
                'date' => Carbon::now()->subDays(5), // 5 days ago
                'status' => 'completed',
                'customer_name' => 'Maria Santos',
                'notes' => 'Walk-in customer',
                'items_count' => 2,
            ],
            [
                'date' => Carbon::now()->subDays(3), // 3 days ago
                'status' => 'completed',
                'customer_name' => 'Jose Reyes',
                'notes' => 'Bulk purchase - member discount applied',
                'items_count' => 5,
            ],
            [
                'date' => Carbon::now()->subDays(2), // 2 days ago
                'status' => 'completed',
                'customer_name' => 'Ana Garcia',
                'notes' => 'Online order - completed payment',
                'items_count' => 4,
            ],
            [
                'date' => Carbon::now()->subDays(1), // 1 day ago
                'status' => 'completed',
                'customer_name' => 'Carlos Mendoza',
                'notes' => 'Regular customer',
                'items_count' => 2,
            ],
            [
                'date' => Carbon::now()->subHours(6), // 6 hours ago
                'status' => 'completed',
                'customer_name' => 'Linda Rodriguez',
                'notes' => 'Morning purchase',
                'items_count' => 3,
            ],
            [
                'date' => Carbon::now()->subHours(3), // 3 hours ago
                'status' => 'cancelled',
                'customer_name' => 'Roberto Martinez',
                'notes' => 'Customer changed mind',
                'items_count' => 1,
            ],
            [
                'date' => Carbon::now()->subHours(1), // 1 hour ago
                'status' => 'completed',
                'customer_name' => 'Elena Lopez',
                'notes' => 'Quick purchase',
                'items_count' => 2,
            ],
            [
                'date' => Carbon::now()->subMinutes(30), // 30 minutes ago
                'status' => 'refunded',
                'customer_name' => 'Mark Hernandez',
                'notes' => 'Mobile order - refunded due to issue',
                'items_count' => 4,
            ],
            [
                'date' => Carbon::now()->subMinutes(15), // 15 minutes ago
                'status' => 'completed',
                'customer_name' => 'Sarah Chen',
                'notes' => 'Tourist purchase',
                'items_count' => 1,
            ],
        ];
        
        foreach ($salesData as $index => $saleData) {
            // Create the sale
            $cashier = $cashiers->random();
            
            $sale = Sale::create([
                'transaction_number' => 'SALE-' . str_pad(($index + 1), 6, '0', STR_PAD_LEFT),
                'user_id' => $cashier->id,
                'subtotal' => 0, // Will be calculated below
                'discount_amount' => 0,
                'tax_amount' => 0,
                'total_amount' => 0, // Will be calculated below
                'cash_received' => 0, // Will be calculated below
                'change_amount' => 0, // Will be calculated below
                'notes' => $saleData['customer_name'] . ' - ' . $saleData['notes'],
                'payment_method' => 'cash',
                'status' => $saleData['status'],
                'created_at' => $saleData['date'],
                'updated_at' => $saleData['date'],
            ]);
            
            // Add random items to the sale
            $selectedProducts = $products->random($saleData['items_count']);
            $totalAmount = 0;
            
            foreach ($selectedProducts as $product) {
                $quantity = rand(1, 5);
                $unitPrice = $product->price;
                $itemTotal = $quantity * $unitPrice;
                $totalAmount += $itemTotal;
                
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $itemTotal,
                    'created_at' => $saleData['date'],
                    'updated_at' => $saleData['date'],
                ]);
                
                // Update product quantity (simulate stock reduction)
                $product->decrement('quantity', $quantity);
            }
            
            // Calculate cash received and change (simulate cash payment with some extra)
            $cashReceived = $totalAmount + rand(0, 100);
            $changeAmount = $cashReceived - $totalAmount;
            
            // Update the sale with calculated amounts
            $sale->update([
                'subtotal' => $totalAmount,
                'total_amount' => $totalAmount,
                'cash_received' => $cashReceived,
                'change_amount' => $changeAmount,
            ]);
        }
        
        $this->command->info('Sample sales data created successfully!');
        $this->command->info('Created ' . count($salesData) . ' sales with multiple items each.');
    }
}
