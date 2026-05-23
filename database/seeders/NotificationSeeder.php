<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing notifications
        Notification::truncate();

        // Get users and products for related notifications
        $users = User::all();
        $products = Product::all();

        // Create sample notifications for different types
        $notifications = [
            // Low stock notifications
            [
                'user_id' => $users->where('role', 'inventory_staff')->first()->id ?? null,
                'title' => 'Low Stock Alert',
                'message' => 'Safeguard Soap 115g is running low on stock. Current quantity: 65 units.',
                'type' => 'low_stock',
                'is_read' => false,
                'related_type' => 'product',
                'related_id' => $products->where('name', 'Safeguard Soap 115g')->first()->id ?? null,
                'created_at' => Carbon::now()->subHours(2),
            ],
            [
                'user_id' => $users->where('role', 'inventory_staff')->first()->id ?? null,
                'title' => 'Low Stock Alert',
                'message' => 'paracetamol is running low on stock. Current quantity: 1 unit.',
                'type' => 'low_stock',
                'is_read' => false,
                'related_type' => 'product',
                'related_id' => $products->where('name', 'paracetamol')->first()->id ?? null,
                'created_at' => Carbon::now()->subHours(4),
            ],
            
            // Out of stock notifications
            [
                'user_id' => $users->where('role', 'inventory_staff')->first()->id ?? null,
                'title' => 'Out of Stock Alert',
                'message' => 'Coca-Cola 1.5L is now out of stock. Please restock soon.',
                'type' => 'out_of_stock',
                'is_read' => false,
                'related_type' => 'product',
                'related_id' => $products->where('name', 'Coca-Cola 1.5L')->first()->id ?? null,
                'created_at' => Carbon::now()->subHours(1),
            ],
            [
                'user_id' => $users->where('role', 'inventory_staff')->first()->id ?? null,
                'title' => 'Out of Stock Alert',
                'message' => 'Bear Brand Milk 1L is now out of stock. Please restock soon.',
                'type' => 'out_of_stock',
                'is_read' => true,
                'related_type' => 'product',
                'related_id' => $products->where('name', 'Bear Brand Milk 1L')->first()->id ?? null,
                'created_at' => Carbon::now()->subHours(3),
            ],
            
            // System notifications
            [
                'user_id' => null, // System-wide notification
                'title' => 'System Maintenance',
                'message' => 'Scheduled system maintenance on April 25, 2026 from 2:00 AM to 4:00 AM.',
                'type' => 'system',
                'is_read' => false,
                'related_type' => null,
                'related_id' => null,
                'created_at' => Carbon::now()->subHours(6),
            ],
            [
                'user_id' => null, // System-wide notification
                'title' => 'New Feature Available',
                'message' => 'Advanced reporting feature is now available in the inventory section.',
                'type' => 'info',
                'is_read' => true,
                'related_type' => null,
                'related_id' => null,
                'created_at' => Carbon::now()->subDays(1),
            ],
            
            // Success notifications
            [
                'user_id' => $users->where('role', 'admin')->first()->id ?? null,
                'title' => 'Sale Completed',
                'message' => 'New sale completed: SALE-000001 for ₱1,234.50.',
                'type' => 'success',
                'is_read' => false,
                'related_type' => 'sale',
                'related_id' => 1,
                'created_at' => Carbon::now()->subMinutes(30),
            ],
            [
                'user_id' => $users->where('role', 'admin')->first()->id ?? null,
                'title' => 'Sale Completed',
                'message' => 'New sale completed: SALE-000002 for ₱567.00.',
                'type' => 'success',
                'is_read' => false,
                'related_type' => 'sale',
                'related_id' => 2,
                'created_at' => Carbon::now()->subMinutes(15),
            ],
            
            // Warning notifications
            [
                'user_id' => $users->where('role', 'admin')->first()->id ?? null,
                'title' => 'Payment Method Issue',
                'message' => 'Customer reported issue with card payment for transaction SALE-000003.',
                'type' => 'warning',
                'is_read' => false,
                'related_type' => 'sale',
                'related_id' => 3,
                'created_at' => Carbon::now()->subMinutes(45),
            ],
            [
                'user_id' => $users->where('role', 'cashier')->first()->id ?? null,
                'title' => 'Low Cash Alert',
                'message' => 'Cash drawer balance is below minimum threshold. Please replenish.',
                'type' => 'warning',
                'is_read' => true,
                'related_type' => null,
                'related_id' => null,
                'created_at' => Carbon::now()->subHours(5),
            ],
            
            // Info notifications
            [
                'user_id' => $users->where('role', 'cashier')->first()->id ?? null,
                'title' => 'Shift Reminder',
                'message' => 'Your shift ends in 30 minutes. Please prepare handover.',
                'type' => 'info',
                'is_read' => false,
                'related_type' => null,
                'related_id' => null,
                'created_at' => Carbon::now()->subMinutes(20),
            ],
            [
                'user_id' => $users->where('role', 'inventory_staff')->first()->id ?? null,
                'title' => 'New Product Added',
                'message' => 'New product "paracetamol" has been added to the inventory.',
                'type' => 'info',
                'is_read' => true,
                'related_type' => 'product',
                'related_id' => $products->where('name', 'paracetamol')->first()->id ?? null,
                'created_at' => Carbon::now()->subDays(2),
            ],
        ];

        // Create notifications
        foreach ($notifications as $notification) {
            Notification::create($notification);
        }

        $this->command->info('Sample notifications created successfully!');
        $this->command->info('Created ' . count($notifications) . ' notifications with various types and statuses.');
    }
}
