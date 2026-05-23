<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::saving(function ($product) {
            // Get original quantity for comparison
            $originalQuantity = $product->getOriginal('quantity');
            $newQuantity = $product->quantity;
            
            // Automatically set product to inactive if quantity is 0 or less
            if ($newQuantity <= 0) {
                $product->is_active = false;
            }
            // Automatically reactivate product if stock is added back (and it was previously inactive)
            elseif ($newQuantity > 0 && !$product->is_active) {
                $product->is_active = true;
            }
            
            // Check for stock level changes and create notifications
            if ($originalQuantity !== null && $originalQuantity !== $newQuantity) {
                // Check if product became out of stock
                if ($originalQuantity > 0 && $newQuantity <= 0) {
                    self::createOutOfStockNotification($product);
                }
                // Check if product recovered from out of stock
                elseif ($originalQuantity <= 0 && $newQuantity > 0) {
                    self::createOutOfStockRecoveryNotification($product);
                    // Clear previous out of stock notifications
                    self::clearOutOfStockNotifications($product);
                }
                // Check if product became low stock (first time crossing threshold)
                elseif ($originalQuantity > $product->min_stock_level && $newQuantity <= $product->min_stock_level && $newQuantity > 0) {
                    self::createLowStockNotification($product);
                }
                // Check if product is already low stock and quantity decreased further (warning)
                elseif ($originalQuantity <= $product->min_stock_level && $newQuantity <= $product->min_stock_level && $newQuantity > 0 && $newQuantity < $originalQuantity) {
                    self::createLowStockWarningNotification($product, $originalQuantity, $newQuantity);
                }
                // Check if product is no longer low stock (restocked)
                elseif ($originalQuantity <= $product->min_stock_level && $newQuantity > $product->min_stock_level) {
                    self::createRestockNotification($product);
                    // Clear previous low stock and out of stock notifications for this product
                    self::clearStockNotifications($product);
                }
            }
        });
    }
    
    /**
     * Create out of stock notification
     */
    protected static function createOutOfStockNotification($product)
    {
        $inventoryStaff = \App\Models\User::where('role', 'inventory_staff')->where('is_active', true)->get();
        
        foreach ($inventoryStaff as $user) {
            \App\Models\Notification::create([
                'user_id' => $user->id,
                'title' => 'Out of Stock Alert',
                'message' => "{$product->name} is now out of stock. Current quantity: {$product->quantity} {$product->unit}. Please restock soon.",
                'type' => 'out_of_stock',
                'related_type' => 'product',
                'related_id' => $product->id,
            ]);
        }
        
        // Also create system-wide notification
        \App\Models\Notification::create([
            'user_id' => null,
            'title' => 'Critical: Out of Stock',
            'message' => "{$product->name} is now out of stock and unavailable for sale.",
            'type' => 'out_of_stock',
            'related_type' => 'product',
            'related_id' => $product->id,
        ]);
    }
    
    /**
     * Create low stock notification
     */
    protected static function createLowStockNotification($product)
    {
        $inventoryStaff = \App\Models\User::where('role', 'inventory_staff')->where('is_active', true)->get();
        
        foreach ($inventoryStaff as $user) {
            \App\Models\Notification::create([
                'user_id' => $user->id,
                'title' => 'Low Stock Alert',
                'message' => "{$product->name} is running low on stock. Current quantity: {$product->quantity} {$product->unit} (Min level: {$product->min_stock_level} {$product->unit}).",
                'type' => 'low_stock',
                'related_type' => 'product',
                'related_id' => $product->id,
            ]);
        }
    }
    
    /**
     * Create low stock warning notification (for already low stock products)
     */
    protected static function createLowStockWarningNotification($product, $originalQuantity, $newQuantity)
    {
        $inventoryStaff = \App\Models\User::where('role', 'inventory_staff')->where('is_active', true)->get();
        
        foreach ($inventoryStaff as $user) {
            \App\Models\Notification::create([
                'user_id' => $user->id,
                'title' => 'Critical Low Stock Warning',
                'message' => "{$product->name} stock decreased from {$originalQuantity} to {$newQuantity} {$product->unit} (Min level: {$product->min_stock_level} {$product->unit}). Immediate restocking recommended.",
                'type' => 'warning',
                'related_type' => 'product',
                'related_id' => $product->id,
            ]);
        }
    }
    
    /**
     * Create out of stock recovery notification
     */
    protected static function createOutOfStockRecoveryNotification($product)
    {
        $inventoryStaff = \App\Models\User::where('role', 'inventory_staff')->where('is_active', true)->get();
        
        foreach ($inventoryStaff as $user) {
            \App\Models\Notification::create([
                'user_id' => $user->id,
                'title' => 'Product Back in Stock',
                'message' => "{$product->name} is now back in stock with {$product->quantity} {$product->unit} available.",
                'type' => 'success',
                'related_type' => 'product',
                'related_id' => $product->id,
            ]);
        }
        
        // Also create system-wide notification
        \App\Models\Notification::create([
            'user_id' => null,
            'title' => 'Good News: Product Restocked',
            'message' => "{$product->name} is now available for purchase again.",
            'type' => 'success',
            'related_type' => 'product',
            'related_id' => $product->id,
        ]);
    }
    
    /**
     * Clear out of stock notifications for a product
     */
    protected static function clearOutOfStockNotifications($product)
    {
        // Mark out of stock notifications as read for this product
        \App\Models\Notification::where('related_type', 'product')
            ->where('related_id', $product->id)
            ->where('type', 'out_of_stock')
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }
    
    /**
     * Clear stock notifications for a product when it's restocked
     */
    protected static function clearStockNotifications($product)
    {
        // Mark low stock notifications as read for this product
        \App\Models\Notification::where('related_type', 'product')
            ->where('related_id', $product->id)
            ->whereIn('type', ['low_stock', 'out_of_stock'])
            ->where('is_read', false)
            ->update(['is_read' => true]);
            
        // Optional: You could also delete them instead of marking as read
        // \App\Models\Notification::where('related_type', 'product')
        //     ->where('related_id', $product->id)
        //     ->whereIn('type', ['low_stock', 'out_of_stock'])
        //     ->delete();
    }
    
    /**
     * Create restock notification
     */
    protected static function createRestockNotification($product)
    {
        $inventoryStaff = \App\Models\User::where('role', 'inventory_staff')->where('is_active', true)->get();
        
        foreach ($inventoryStaff as $user) {
            \App\Models\Notification::create([
                'user_id' => $user->id,
                'title' => 'Product Restocked',
                'message' => "{$product->name} has been restocked. Current quantity: {$product->quantity} {$product->unit}.",
                'type' => 'success',
                'related_type' => 'product',
                'related_id' => $product->id,
            ]);
        }
    }

    protected $fillable = [
        'name',
        'sku',
        'description',
        'category_id',
        'unit',
        'price',
        'cost_price',
        'quantity',
        'min_stock_level',
        'max_stock_level',
        'barcode',
        'image',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'quantity' => 'integer',
        'min_stock_level' => 'integer',
        'max_stock_level' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the sale items for the product.
     */
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Get the inventory movements for the product.
     */
    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    /**
     * Check if product is low in stock.
     */
    public function isLowStock()
    {
        return $this->quantity <= $this->min_stock_level;
    }

    /**
     * Check if product is out of stock.
     */
    public function isOutOfStock()
    {
        return $this->quantity <= 0;
    }

    /**
     * Get the profit margin percentage.
     */
    public function getProfitMarginAttribute()
    {
        if ($this->cost_price > 0) {
            return (($this->price - $this->cost_price) / $this->cost_price) * 100;
        }
        return 0;
    }
}
