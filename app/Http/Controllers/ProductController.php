<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by stock status
        if ($request->has('stock_status')) {
            switch ($request->stock_status) {
                case 'in_stock':
                    $query->where('quantity', '>', 0);
                    break;
                case 'low_stock':
                    $query->whereRaw('quantity <= min_stock_level AND quantity > 0');
                    break;
                case 'out_of_stock':
                    $query->where('quantity', '<=', 0);
                    break;
            }
        }

        $products = $query->orderBy('name')->paginate(15);
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        // Get unread notifications
        $unreadNotifications = \App\Models\Notification::where('user_id', auth()->id())
            ->orWhereNull('user_id')
            ->unread()
            ->latest()
            ->take(5)
            ->get();

        return view('products.index', compact('products', 'categories', 'unreadNotifications'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:products',
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required|string|max:20',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'min_stock_level' => 'required|integer|min:0',
            'max_stock_level' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'barcode' => 'nullable|string|max:50|unique:products',
            'is_active' => 'nullable|boolean',
        ]);

        $productData = $request->all();
        $productData['is_active'] = $request->has('is_active');
        
        $product = Product::create($productData);

        // Create inventory movement record
        \App\Models\InventoryMovement::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'movement_type' => 'stock_in',
            'quantity' => $product->quantity,
            'previous_quantity' => 0,
            'new_quantity' => $product->quantity,
            'reason' => 'Initial stock entry',
            'unit_cost' => $product->cost_price,
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'inventoryMovements' => function($query) {
            $query->with('user')->latest()->take(10);
        }]);

        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:products,sku,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required|string|max:20',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'min_stock_level' => 'required|integer|min:0',
            'max_stock_level' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'barcode' => 'nullable|string|max:50|unique:products,barcode,' . $product->id,
            'is_active' => 'nullable|boolean',
        ]);

        $oldQuantity = $product->quantity;
        
        $productData = $request->all();
        $productData['is_active'] = $request->has('is_active');
        
        $product->update($productData);

        // Create inventory movement if quantity changed
        if ($oldQuantity != $product->quantity) {
            \App\Models\InventoryMovement::create([
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'movement_type' => 'adjustment',
                'quantity' => $product->quantity - $oldQuantity,
                'previous_quantity' => $oldQuantity,
                'new_quantity' => $product->quantity,
                'reason' => 'Stock adjustment during product update',
            ]);
        }

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Check if product has sales
        if ($product->saleItems()->exists()) {
            return redirect()->route('products.index')
                ->with('error', 'Cannot delete product with existing sales records.');
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * Update stock quantity.
     */
    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
            'reason' => 'required|string|max:255',
            'movement_type' => 'required|in:stock_in,stock_out,adjustment',
        ]);

        $oldQuantity = $product->quantity;
        $newQuantity = $request->quantity;
        $quantityChange = $newQuantity - $oldQuantity;

        // Validate stock out doesn't exceed available quantity
        if ($request->movement_type === 'stock_out' && $quantityChange > $oldQuantity) {
            return back()->with('error', 'Cannot stock out more than available quantity.');
        }

        $product->update(['quantity' => $newQuantity]);

        // Create inventory movement
        \App\Models\InventoryMovement::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'movement_type' => $request->movement_type,
            'quantity' => abs($quantityChange),
            'previous_quantity' => $oldQuantity,
            'new_quantity' => $newQuantity,
            'reason' => $request->reason,
        ]);

        return back()->with('success', 'Stock updated successfully.');
    }
}
