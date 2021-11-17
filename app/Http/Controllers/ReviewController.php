<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Product;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index(Product $product)
    {
        $sort = request('sort', 'created_at.desc');

        return ReviewResource::collection(
            Review::where('product_id', $product->id)
                ->withSort($sort)
                ->with('user:id,first_name,last_name')
                ->get()
        );
    }

    public function store(Product $product, StoreReviewRequest $request)
    {
        auth()->user()->reviews()->create([
            'product_id' => $product->id,
            'title' => $request->title,
            'content' => $request->content,
            'rating' => $request->rating,
        ]);

        return $this->success([], 201);
    }
}
