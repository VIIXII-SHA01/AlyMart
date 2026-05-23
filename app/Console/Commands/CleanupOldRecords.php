<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\InventoryMovement;
use App\Models\Notification;

class CleanupOldRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanup-old-records {--days=37 : Number of days to keep records (default: 37 days = 1 month + 7 days)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically delete old sales and inventory records (older than 1 month and 7 days)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting cleanup of old records...');
        
        // Get the number of days to keep (default: 37 days = 1 month + 7 days)
        $days = $this->option('days');
        
        // Define cutoff date
        $cutoffDate = Carbon::now()->subDays($days);
        $this->info("Deleting records older than: " . $cutoffDate->format('Y-m-d H:i:s'));
        $this->info("Keeping records from the last {$days} days");
        
        $deletedSales = 0;
        $deletedSaleItems = 0;
        $deletedMovements = 0;
        $deletedNotifications = 0;
        
        try {
            DB::beginTransaction();
            
            // Get old sales to delete
            $oldSales = Sale::where('created_at', '<', $cutoffDate)->get();
            $oldSaleIds = $oldSales->pluck('id');
            
            if ($oldSaleIds->isNotEmpty()) {
                // Delete sale items first (foreign key constraint)
                $deletedSaleItems = SaleItem::whereIn('sale_id', $oldSaleIds)->delete();
                
                // Delete the sales
                $deletedSales = Sale::whereIn('id', $oldSaleIds)->delete();
                
                $this->info("Deleted {$deletedSales} old sales and {$deletedSaleItems} sale items");
            }
            
            // Delete old inventory movements
            $deletedMovements = InventoryMovement::where('created_at', '<', $cutoffDate)->delete();
            $this->info("Deleted {$deletedMovements} old inventory movements");
            
            // Delete old notifications (keep only recent ones)
            $deletedNotifications = Notification::where('created_at', '<', $cutoffDate)->delete();
            $this->info("Deleted {$deletedNotifications} old notifications");
            
            DB::commit();
            
            $this->info('Cleanup completed successfully!');
            $this->info('Summary:');
            $this->info("- Sales deleted: {$deletedSales}");
            $this->info("- Sale items deleted: {$deletedSaleItems}");
            $this->info("- Inventory movements deleted: {$deletedMovements}");
            $this->info("- Notifications deleted: {$deletedNotifications}");
            
            // Log the cleanup
            \Log::info('Old records cleanup completed', [
                'cutoff_date' => $cutoffDate->toDateTimeString(),
                'deleted_sales' => (int) $deletedSales,
                'deleted_sale_items' => (int) $deletedSaleItems,
                'deleted_movements' => (int) $deletedMovements,
                'deleted_notifications' => (int) $deletedNotifications,
                'run_at' => Carbon::now()->toDateTimeString()
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Cleanup failed: ' . $e->getMessage());
            
            // Log the error
            \Log::error('Old records cleanup failed', [
                'error' => $e->getMessage(),
                'run_at' => Carbon::now()
            ]);
            
            return 1;
        }
        
        return 0;
    }
}
