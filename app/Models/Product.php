<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'image_path',
    ];

    public function scopeSearch($query, $search) {
        return $query->when(trim($search), function($query) use($search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        });
    }

    public function carts()
    {
        return $this->belongsToMany(Cart::class, 'cart_product')->withTimestamps();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product')->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'product_user')->withTimestamps();
    }
}
