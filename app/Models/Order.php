<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Order extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'cart_id', 'total_amount', 'status',
        'order_date', 'paid_at', 'shipped_at', 'delivered_at', 'shipping_info'
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'paid_at' => 'datetime', 
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class); // Adjust if your table is named differently
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }
}
