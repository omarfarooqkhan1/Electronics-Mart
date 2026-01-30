<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate cart totals (prices already include tax)
     */
    public function getTotals()
    {
        $subtotal = $this->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        return [
            'subtotal' => $subtotal,
            'tax_component' => $subtotal * 0.153, // Show 15.3% tax component for transparency
            'shipping' => 0, // Free shipping
            'total' => $subtotal
        ];
    }
}