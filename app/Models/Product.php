<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    protected $hidden = ['pivot'];

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'heigth',
        'weigth',
        'image_path',
    ];

    public function scopeSearch($query, $search)
    {
        return $query->when(trim($search), function($query) use($search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        });
    }

    public function scopeWithCategories($query, $categories)
    {
        return $query->when($categories, function($query) use($categories) {
            $query->whereHas('categories', function($query) use($categories) {
                $cat = explode(',', $categories);
                    $query->whereIn('category_id', $cat);
            });
        });
    }

    public function scopeWithCategory($query, $category)
    {
        return $query->when($category, function($query) use($category) {
            $query
                ->join('category_product', 'products.id', '=', 'category_product.product_id')
                ->join('categories', 'categories.id', '=', 'category_product.category_id')
                ->select('products.*')
                ->where('categories.id', $category->id);
        });
    }

    public function scopeWithMinPrice($query, $min)
    {
        return $query->when($min, function($query) use($min) {
            $query->where('price', '>',  $min);
        });
    }

    public function scopeWithMaxPrice($query, $max)
    {
        return $query->when($max, function($query) use($max) {
            $query->where('price', '<',  $max);
        });
    }

    public function scopeWithSize($query, $size)
    {
        return $query->when($size, function($query) use($size) {
            switch ($size) {
                case 'xs':
                    $query->whereBetween('height', ['2', '15']);
                    break;
                case 's':
                    $query->whereBetween('height', ['16', '35']);
                    break;
                case 'm':
                    $query->whereBetween('height', ['36', '50']);
                    break;
                case 'l':
                    $query->whereBetween('height', ['51', '100']);
                    break;
                case 'xl':
                    $query->where('height', '>=', '101');
                    break;
            }
        });
    }

    public function scopeWithSort($query, $sort)
    {
        return $query->when($sort, function($query) use($sort) {
            switch ($sort) {
                case 'name.desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'name.asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'price.desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'price.asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'review.desc':
                    $query->orderBy('reviews_count', 'desc');
                    break;
            }
        });
    }

    public function scopeWithRatings($query, $ratings)
    {
        return $query->when($ratings, function($query) use($ratings) {
            $query
                ->join('reviews', 'products.id', '=', 'reviews.product_id')
                ->select('products.*', DB::raw('ROUND(AVG(reviews.rating))'))
                ->groupBy('products.id')
                ->havingRaw('ROUND(AVG(reviews.rating)) = ?', [$ratings])
                ->get();
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

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'product_order')->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
