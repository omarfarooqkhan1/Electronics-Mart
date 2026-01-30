<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'subtotal',
        'tax',
        'shipping',
        'total',
        'notes',
        'tracking_number',
        'shipping_service',
        'shipping_name',
        'shipping_email',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_postal_code',
        'shipping_country',
        'billing_same_as_shipping',
        'billing_name',
        'billing_email',
        'billing_phone',
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_postal_code',
        'billing_country',
        'payment_method',
        'payment_status',
        'payment_transaction_id',
    ];

    /**
     * Generate a unique order number.
     */
    public static function generateOrderNumber(): string
    {
        $prefix = 'ORD';
        $timestamp = now()->format('YmdHis');
        $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));
        return $prefix . $timestamp . $random;
    }

    /**
     * Get the user that owns the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items for the order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    
    /**
     * Calculate order totals.
     */
    public function calculateTotals(): void
    {
        // Calculate subtotal from items (prices already include tax)
        $subtotal = $this->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        
        // Set values - tax is included in price, but we show breakdown for transparency
        $this->subtotal = $subtotal;
        $this->tax = $subtotal * 0.153; // Show 15.3% tax component (18% GST / 1.18)
        $this->shipping = 0; // Free shipping
        $this->total = $subtotal; // Total is same as subtotal since tax is included
        
        $this->save();
    }
}
