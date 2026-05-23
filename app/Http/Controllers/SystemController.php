<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SystemController extends Controller
{
    /**
     * Show system maintenance page.
     */
    public function maintenance()
    {
        return view('system.maintenance');
    }

    /**
     * Run cleanup of old records.
     */
    public function runCleanup(Request $request)
    {
        $days = $request->input('days', 37); // Default 37 days (1 month + 7 days)
        
        try {
            // Run the cleanup command
            $exitCode = Artisan::call('app:cleanup-old-records', [
                '--days' => $days
            ]);
            
            $output = Artisan::output();
            
            if ($exitCode === 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cleanup completed successfully!',
                    'output' => $output
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Cleanup failed!',
                    'output' => $output
                ], 500);
            }
            
        } catch (\Exception $e) {
            Log::error('Manual cleanup failed', [
                'error' => $e->getMessage(),
                'days' => $days
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Cleanup failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get system statistics.
     */
    public function statistics()
    {
        $stats = [
            'total_sales' => \App\Models\Sale::count(),
            'total_sale_items' => \App\Models\SaleItem::count(),
            'total_movements' => \App\Models\InventoryMovement::count(),
            'total_notifications' => \App\Models\Notification::count(),
            'old_sales' => \App\Models\Sale::where('created_at', '<', Carbon::now()->subDays(37))->count(),
            'old_movements' => \App\Models\InventoryMovement::where('created_at', '<', Carbon::now()->subDays(37))->count(),
            'old_notifications' => \App\Models\Notification::where('created_at', '<', Carbon::now()->subDays(37))->count(),
        ];
        
        return response()->json($stats);
    }
}
