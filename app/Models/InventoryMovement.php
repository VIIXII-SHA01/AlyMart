<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'movement_type',
        'quantity',
        'previous_quantity',
        'new_quantity',
        'reason',
        'unit_cost',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'previous_quantity' => 'integer',
        'new_quantity' => 'integer',
        'unit_cost' => 'decimal:2',
    ];

    /**
     * Get the product that owns the inventory movement.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user that owns the inventory movement.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get only stock-in movements.
     */
    public function scopeStockIn($query)
    {
        return $query->where('movement_type', 'stock_in');
    }

    /**
     * Scope to get only stock-out movements.
     */
    public function scopeStockOut($query)
    {
        return $query->where('movement_type', 'stock_out');
    }

    /**
     * Scope to get only sale movements.
     */
    public function scopeSales($query)
    {
        return $query->where('movement_type', 'sale');
    }

    /**
     * Scope to get only adjustment movements.
     */
    public function scopeAdjustments($query)
    {
        return $query->where('movement_type', 'adjustment');
    }
}
