<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'category_id',
        'brand_id', // Added brand_id
        'name',
        'description',
        'price',
        'stock',
        'main_image',
        'images',
        'status',
        'brand',
        'published_at',
        'name_snapshot',
    ];


    protected $casts = [
        'images' => 'array',
        'published_at' => 'date',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the brand that this product belongs to.
     */
    public function brandRelation()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    /**
     * Get all cart items for this product.
     */
    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

}
