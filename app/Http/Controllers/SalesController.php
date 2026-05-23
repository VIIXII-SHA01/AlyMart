<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesController extends Controller
{
    /**
     * Display sales dashboard and listing.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Get sales with filters
        $sales = Sale::with(['user', 'saleItems.product'])
            ->when($request->date_from, function($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->date_to, function($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->date_to);
            })
            ->when($request->status, function($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Get statistics
        $todaySales = Sale::whereDate('created_at', Carbon::today())->sum('total_amount');
        $weekSales = Sale::whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->sum('total_amount');
        $monthSales = Sale::whereMonth('created_at', Carbon::now())
            ->whereYear('created_at', Carbon::now())
            ->sum('total_amount');
        
        $totalTransactions = Sale::count();
        $todayTransactions = Sale::whereDate('created_at', Carbon::today())->count();

        // Get cashiers for filter dropdown
        $cashiers = User::where('role', 'cashier')->where('is_active', true)->get();

        // Get unread notifications
        $unreadNotifications = \App\Models\Notification::where('user_id', auth()->id())
            ->orWhereNull('user_id')
            ->unread()
            ->latest()
            ->take(5)
            ->get();

        return view('sales.index', compact(
            'sales', 
            'todaySales', 
            'weekSales', 
            'monthSales',
            'totalTransactions',
            'todayTransactions',
            'cashiers',
            'unreadNotifications'
        ));
    }

    /**
     * Show the form for creating a new sale.
     */
    public function create()
    {
        $products = Product::where('is_active', true)
            ->where('quantity', '>', 0)
            ->orderBy('name')
            ->get();
            
        return view('sales.create', compact('products'));
    }

    /**
     * Store a newly created sale.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'payment_method' => 'required|in:cash,card,gcash,other',
                'customer_name' => 'nullable|string|max:255',
                'notes' => 'nullable|string|max:1000'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Calculate total amount
            $totalAmount = 0;
            $saleItems = [];

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                // Check stock availability
                if ($product->quantity < $item['quantity']) {
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient stock for {$product->name}. Available: {$product->quantity}"
                    ], 400);
                }

                $subtotal = $product->price * $item['quantity'];
                $totalAmount += $subtotal;

                $saleItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'total_price' => $subtotal,
                    'discount' => 0
                ];
            }

            // Create sale
            $sale = Sale::create([
                'transaction_number' => 'SALE-' . strtoupper(uniqid()),
                'user_id' => auth()->id(),
                'subtotal' => $totalAmount,
                'discount_amount' => 0,
                'tax_amount' => 0,
                'total_amount' => $totalAmount,
                'cash_received' => $totalAmount,
                'change_amount' => 0,
                'payment_method' => $request->payment_method,
                'customer_name' => $request->customer_name,
                'notes' => $request->notes,
                'status' => 'completed'
            ]);

            // Create sale items and update inventory
            foreach ($saleItems as $item) {
                $saleItem = new SaleItem($item);
                $sale->saleItems()->save($saleItem);

                // Update product quantity
                $product = Product::find($item['product_id']);
                $oldQuantity = $product->quantity;
                $product->quantity -= $item['quantity'];
                $product->save();

                // Create inventory movement
                $product->inventoryMovements()->create([
                    'user_id' => auth()->id(),
                    'movement_type' => 'stock_out',
                    'quantity' => $item['quantity'],
                    'previous_quantity' => $oldQuantity,
                    'new_quantity' => $product->quantity,
                    'reason' => 'Sale #' . $sale->transaction_number,
                    'unit_cost' => $product->cost_price
                ]);
            }

            DB::commit();
            
            // Create success notification for admin and cashier
            $notificationUsers = \App\Models\User::where('role', 'admin')
                ->orWhere('id', auth()->id())
                ->where('is_active', true)
                ->get();
            
            foreach ($notificationUsers as $user) {
                \App\Models\Notification::create([
                    'user_id' => $user->id,
                    'title' => 'Sale Completed',
                    'message' => "New sale completed: {$sale->transaction_number} for ₱" . number_format($totalAmount, 2) . ".",
                    'type' => 'success',
                    'related_type' => 'sale',
                    'related_id' => $sale->id,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Sale completed successfully!',
                'sale_id' => $sale->id,
                'transaction_number' => $sale->transaction_number
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error processing sale: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified sale.
     */
    public function show(Sale $sale)
    {
        $sale->load(['user', 'saleItems.product']);
        return view('sales.show', compact('sale'));
    }

    /**
     * Show the form for editing the specified sale.
     */
    public function edit(Sale $sale)
    {
        // Only allow editing of pending sales
        if ($sale->status !== 'pending') {
            return redirect()->route('sales.index')
                ->with('error', 'Only pending sales can be edited.');
        }

        $sale->load(['saleItems.product']);
        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('sales.edit', compact('sale', 'products'));
    }

    /**
     * Update the specified sale.
     */
    public function update(Request $request, Sale $sale)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,cancelled',
            'notes' => 'nullable|string|max:1000'
        ]);

        $sale->update([
            'status' => $request->status,
            'notes' => $request->notes
        ]);

        return redirect()->route('sales.show', $sale)
            ->with('success', 'Sale updated successfully!');
    }

    /**
     * Remove the specified sale.
     */
    public function destroy(Sale $sale)
    {
        // Only allow deletion of pending sales
        if ($sale->status !== 'pending') {
            return redirect()->route('sales.index')
                ->with('error', 'Only pending sales can be deleted.');
        }

        try {
            DB::beginTransaction();

            // Restore inventory
            foreach ($sale->saleItems as $item) {
                $product = $item->product;
                $oldQuantity = $product->quantity;
                $product->quantity += $item->quantity;
                $product->save();

                // Create inventory movement
                $product->inventoryMovements()->create([
                    'user_id' => auth()->id(),
                    'movement_type' => 'stock_in',
                    'quantity' => $item->quantity,
                    'previous_quantity' => $oldQuantity,
                    'new_quantity' => $product->quantity,
                    'reason' => 'Sale cancelled #' . $sale->transaction_number,
                    'unit_cost' => $product->cost_price
                ]);
            }

            // Delete sale items and sale
            $sale->saleItems()->delete();
            $sale->delete();

            DB::commit();

            return redirect()->route('sales.index')
                ->with('success', 'Sale deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('sales.index')
                ->with('error', 'Error deleting sale: ' . $e->getMessage());
        }
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
            'selling_price' => $product->selling_price,
            'quantity' => $product->quantity,
            'unit' => $product->unit
        ]);
    }

    /**
     * Generate receipt for a sale.
     */
    public function receipt(Sale $sale)
    {
        $sale->load(['user', 'saleItems.product']);
        return view('sales.receipt', compact('sale'));
    }
}
