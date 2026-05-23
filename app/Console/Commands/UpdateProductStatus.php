<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class UpdateProductStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update product status based on stock quantity (0 or less = inactive)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating product status based on stock quantity...');
        
        // Find products with zero or negative quantity that are still active
        $productsToDeactivate = Product::where('quantity', '<=', 0)
            ->where('is_active', true)
            ->get();
        
        $deactivatedCount = 0;
        foreach ($productsToDeactivate as $product) {
            $product->is_active = false;
            $product->save();
            $deactivatedCount++;
            $this->line("Deactivated: {$product->name} (Qty: {$product->quantity})");
        }
        
        // Find products with positive quantity that are inactive
        $productsToActivate = Product::where('quantity', '>', 0)
            ->where('is_active', false)
            ->get();
        
        $activatedCount = 0;
        foreach ($productsToActivate as $product) {
            $product->is_active = true;
            $product->save();
            $activatedCount++;
            $this->line("Activated: {$product->name} (Qty: {$product->quantity})");
        }
        
        $this->newLine();
        $this->info("Product status update completed!");
        $this->info("Products deactivated: {$deactivatedCount}");
        $this->info("Products activated: {$activatedCount}");
        
        return 0;
    }
}
