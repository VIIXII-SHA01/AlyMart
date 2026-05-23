<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\InventoryMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InventoryController extends Controller
{
    /**
     * Display inventory dashboard and movements.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Get inventory movements with filters
        $movements = InventoryMovement::with(['product', 'user'])
            ->when($request->date_from, function($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->date_to, function($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->date_to);
            })
            ->when($request->type, function($query) use ($request) {
                $query->where('movement_type', $request->type);
            })
            ->when($request->product_id, function($query) use ($request) {
                $query->where('product_id', $request->product_id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Get products for filter dropdown
        $products = Product::orderBy('name')->get();

        // Get statistics
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $lowStockProducts = Product::whereRaw('quantity <= min_stock_level AND quantity > 0')
            ->where('is_active', true)->count();
        $outOfStockProducts = Product::where('quantity', 0)->count();
        
        // Get today's movements
        $todayMovements = InventoryMovement::whereDate('created_at', Carbon::today())->count();
        $stockInToday = InventoryMovement::whereDate('created_at', Carbon::today())
            ->where('movement_type', 'stock_in')
            ->sum('quantity');
        $stockOutToday = InventoryMovement::whereDate('created_at', Carbon::today())
            ->whereIn('movement_type', ['sale', 'stock_out'])
            ->sum('quantity');

        // Get recent low stock alerts
        $lowStockAlerts = Product::whereRaw('quantity <= min_stock_level AND quantity > 0')
            ->where('is_active', true)
            ->orderBy('quantity', 'asc')
            ->limit(5)
            ->get();

        // Get recent out of stock alerts
        $outOfStockAlerts = Product::where('quantity', 0)
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        // Get unread notifications
        $unreadNotifications = \App\Models\Notification::where('user_id', auth()->id())
            ->orWhereNull('user_id')
            ->unread()
            ->latest()
            ->take(5)
            ->get();

        return view('inventory.index', compact(
            'movements', 
            'products',
            'totalProducts', 
            'activeProducts', 
            'lowStockProducts', 
            'outOfStockProducts',
            'todayMovements',
            'stockInToday',
            'stockOutToday',
            'lowStockAlerts',
            'outOfStockAlerts',
            'unreadNotifications'
        ));
    }

    /**
     * Show the form for creating a new inventory movement.
     */
    public function create()
    {
        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get();
            
        return view('inventory.create', compact('products'));
    }

    /**
     * Store a newly created inventory movement.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:stock_in,stock_out,adjustment,stock_return',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
            'unit_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000'
        ]);

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($request->product_id);
            $oldQuantity = $product->quantity;

            // Calculate new quantity based on movement type
            $newQuantity = $oldQuantity;
            switch ($request->type) {
                case 'stock_in':
                    $newQuantity += $request->quantity;
                    break;
                case 'stock_out':
                case 'sale':
                    if ($oldQuantity < $request->quantity) {
                        return response()->json([
                            'success' => false,
                            'message' => "Insufficient stock. Available: {$oldQuantity}"
                        ], 400);
                    }
                    $newQuantity -= $request->quantity;
                    break;
                case 'stock_return':
                    $newQuantity += $request->quantity;
                    break;
                case 'adjustment':
                    // For adjustments, quantity can be positive or negative
                    if ($request->adjustment_type == 'increase') {
                        $newQuantity += $request->quantity;
                    } else {
                        if ($oldQuantity < $request->quantity) {
                            return response()->json([
                                'success' => false,
                                'message' => "Cannot decrease below zero. Current: {$oldQuantity}"
                            ], 400);
                        }
                        $newQuantity -= $request->quantity;
                    }
                    break;
            }

            // Update product quantity
            $product->quantity = $newQuantity;
            if ($request->unit_cost && $request->type == 'stock_in') {
                $product->cost_price = $request->unit_cost;
            }
            $product->save();

            // Create inventory movement
            $movement = InventoryMovement::create([
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'movement_type' => $request->type,
                'quantity' => $request->quantity,
                'previous_quantity' => $oldQuantity,
                'new_quantity' => $newQuantity,
                'reason' => $request->reason,
                'unit_cost' => $request->unit_cost ?? $product->cost_price,
                'notes' => $request->notes
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Inventory movement recorded successfully!',
                'movement_id' => $movement->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error recording movement: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified inventory movement.
     */
    public function show(InventoryMovement $movement)
    {
        $movement->load(['product', 'user']);
        return view('inventory.show', compact('movement'));
    }

    /**
     * Show low stock products.
     */
    public function lowStock()
    {
        $lowStockProducts = Product::whereRaw('quantity <= min_stock_level AND quantity > 0')
            ->where('is_active', true)
            ->orderBy('quantity', 'asc')
            ->paginate(20);

        return view('inventory.low-stock', compact('lowStockProducts'));
    }

    /**
     * Show out of stock products.
     */
    public function outOfStock()
    {
        $outOfStockProducts = Product::where('quantity', 0)
            ->where('is_active', true)
            ->orderBy('name')
            ->paginate(20);

        return view('inventory.out-of-stock', compact('outOfStockProducts'));
    }

    /**
     * Show inventory reports.
     */
    public function reports(Request $request)
    {
        $period = $request->get('period', '30days'); // Default to 30 days
        
        // Calculate start date based on period
        switch($period) {
            case 'today':
                $startDate = Carbon::now()->startOfDay();
                $periodLabel = 'Today (' . Carbon::now()->format('M d, Y') . ')';
                break;
            case '7days':
                $startDate = Carbon::now()->subDays(7);
                $periodLabel = 'Last 7 Days';
                break;
            case '30days':
                $startDate = Carbon::now()->subDays(30);
                $periodLabel = 'Last 30 Days';
                break;
            case 'weekly':
                $startDate = Carbon::now()->subWeeks(4);
                $periodLabel = 'Last 4 Weeks';
                break;
            case 'custom':
                $customDate = $request->get('date');
                if ($customDate) {
                    $startDate = Carbon::parse($customDate)->startOfDay();
                    $periodLabel = 'Custom Date (' . Carbon::parse($customDate)->format('M d, Y') . ')';
                } else {
                    $startDate = Carbon::now()->subDays(30);
                    $periodLabel = 'Last 30 Days';
                }
                break;
            default:
                $startDate = Carbon::now()->subDays(30);
                $periodLabel = 'Last 30 Days';
                break;
        }
        
        $dailyMovements = InventoryMovement::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, movement_type, SUM(quantity) as total')
            ->groupBy('date', 'movement_type')
            ->orderBy('date')
            ->get();

        // Group by date for chart
        $chartData = [];
        foreach ($dailyMovements as $movement) {
            if (!isset($chartData[$movement->date])) {
                $chartData[$movement->date] = [
                    'date' => $movement->date,
                    'stock_in' => 0,
                    'stock_out' => 0,
                    'sale' => 0,
                    'adjustment' => 0
                ];
            }
            $chartData[$movement->date][$movement->movement_type] = $movement->total;
            
            // If this is a stock_out movement, also count it as a sale
            if ($movement->movement_type === 'stock_out') {
                $chartData[$movement->date]['sale'] = $movement->total;
            }
        }
        
        // Create weekly summary if period is weekly
        $weeklyData = [];
        if ($period === 'weekly') {
            $weeklyMovements = InventoryMovement::where('created_at', '>=', $startDate)
                ->selectRaw('YEARWEEK(created_at) as week, movement_type, SUM(quantity) as total')
                ->groupBy('week', 'movement_type')
                ->orderBy('week')
                ->get();
                
            foreach ($weeklyMovements as $movement) {
                if (!isset($weeklyData[$movement->week])) {
                    $weeklyData[$movement->week] = [
                        'week' => $movement->week,
                        'stock_in' => 0,
                        'stock_out' => 0,
                        'sale' => 0,
                        'adjustment' => 0
                    ];
                }
                $weeklyData[$movement->week][$movement->movement_type] = $movement->total;
                
                // If this is a stock_out movement, also count it as a sale
                if ($movement->movement_type === 'stock_out') {
                    $weeklyData[$movement->week]['sale'] = $movement->total;
                }
            }
        }

        // Get top products by movement
        $topProducts = InventoryMovement::with('product')
            ->selectRaw('product_id, SUM(quantity) as total_movement')
            ->where('created_at', '>=', $startDate)
            ->groupBy('product_id')
            ->orderBy('total_movement', 'desc')
            ->limit(10)
            ->get();

        // Calculate total income from sales
        $salesMovements = InventoryMovement::with('product')
            ->where('movement_type', 'stock_out')
            ->where('created_at', '>=', $startDate)
            ->get();
            
        $totalIncome = 0;
        foreach ($salesMovements as $movement) {
            if ($movement->product) {
                $totalIncome += $movement->quantity * $movement->product->price;
            }
        }

        return view('inventory.reports', compact('chartData', 'weeklyData', 'topProducts', 'period', 'periodLabel', 'startDate', 'totalIncome'));
    }

    /**
     * Get product details for AJAX requests.
     */
    public function getProductDetails($id)
    {
        $product = Product::findOrFail($id);
        
        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'quantity' => $product->quantity,
            'min_stock_level' => $product->min_stock_level,
            'unit' => $product->unit,
            'cost_price' => $product->cost_price,
            'selling_price' => $product->selling_price
        ]);
    }

    /**
     * Bulk stock update.
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'updates' => 'required|array',
            'updates.*.product_id' => 'required|exists:products,id',
            'updates.*.quantity' => 'required|integer|min:0',
            'reason' => 'required|string|max:255'
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->updates as $update) {
                $product = Product::findOrFail($update['product_id']);
                $oldQuantity = $product->quantity;
                $newQuantity = $update['quantity'];

                if ($newQuantity != $oldQuantity) {
                    // Update product quantity
                    $product->quantity = $newQuantity;
                    $product->save();

                    // Create inventory movement
                    $movementType = $newQuantity > $oldQuantity ? 'stock_in' : 'stock_out';
                    $quantity = abs($newQuantity - $oldQuantity);

                    InventoryMovement::create([
                        'product_id' => $product->id,
                        'user_id' => auth()->id(),
                        'type' => $movementType,
                        'quantity' => $quantity,
                        'previous_quantity' => $oldQuantity,
                        'new_quantity' => $newQuantity,
                        'reason' => $request->reason . ' (Bulk Update)',
                        'unit_cost' => $product->cost_price
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bulk update completed successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error during bulk update: ' . $e->getMessage()
            ], 500);
        }
    }
}
