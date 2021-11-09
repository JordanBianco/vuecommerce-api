<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReviewResource;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Product $product)
    {
        $sort = request('sort');

        return ReviewResource::collection(
            Review::where('product_id', $product->id)
                ->withSort($sort)
                ->get()
        );
    }
}
