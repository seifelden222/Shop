<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'slug',
        'image',
        'is_active',
    ];
    
    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id', 'id');
    }
    

}
