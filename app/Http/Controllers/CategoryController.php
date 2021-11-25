<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    public function index()
    {
        return CategoryResource::collection(
            Category::all()
        );
    }

    public function show(Category $category)
    {
        $min = request('min_price');
        $max = request('max_price');
        $ratings = request('ratings');
    
        return ProductResource::collection(
            Product::withCategory($category)
                ->withRatings($ratings)
                ->withMinPrice($min)
                ->withMaxPrice($max)
                ->with('reviews')
                ->paginate(10)
        );
    }
}
