<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_number',
        'user_id',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'total_amount',
        'cash_received',
        'change_amount',
        'notes',
        'payment_method',
        'status',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'cash_received' => 'decimal:2',
        'change_amount' => 'decimal:2',
    ];

    /**
     * Get the user that made the sale.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the sale items for the sale.
     */
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Get the customer name from notes.
     */
    public function getCustomerNameAttribute()
    {
        if ($this->notes) {
            // Extract customer name from notes (format: "Customer Name - Notes")
            $parts = explode(' - ', $this->notes, 2);
            return $parts[0] ?? 'Walk-in Customer';
        }
        return 'Walk-in Customer';
    }

    /**
     * Get the clean notes (without customer name).
     */
    public function getCleanNotesAttribute()
    {
        if ($this->notes) {
            // Extract notes part (format: "Customer Name - Notes")
            $parts = explode(' - ', $this->notes, 2);
            return $parts[1] ?? $this->notes;
        }
        return '';
    }

    /**
     * Get the number of items in this sale.
     */
    public function getItemsCountAttribute()
    {
        return $this->saleItems()->count();
    }

    /**
     * Generate unique transaction number.
     */
    public static function generateTransactionNumber()
    {
        $prefix = 'SALE';
        $date = date('Ymd');
        $lastSale = self::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();
        
        $sequence = $lastSale ? intval(substr($lastSale->transaction_number, -4)) + 1 : 1;
        
        return $prefix . $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
