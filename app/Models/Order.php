<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use SoftDeletes, HasFactory;
    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'city',
        'postal_code',
        'notes',
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
        'shipping_cost' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = \Illuminate\Support\Str::uuid();
            }
        });
    }

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

    // Order belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
     