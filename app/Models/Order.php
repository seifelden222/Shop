<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'total_price',
        'description',
        'address',
        'status',
        'shipping_cost',
        'order_number',
        'currency',
        'provider_order_id',
        'payment_method',
        'payment_status',
        'transaction_id',

    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    // One order has many items
    public function orderItems()
    {
        return $this->hasMany(Cart::class);
    }

    // Alias for cart items
    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

}
     