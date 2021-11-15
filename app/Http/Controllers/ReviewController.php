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
        $sort = request('sort', 'created_at.desc');

        return ReviewResource::collection(
            Review::where('product_id', $product->id)
                ->withSort($sort)
                ->with('user:id,first_name,last_name')
                ->get()
        );
    }

    public function store(Product $product, Request $request)
    {
        $request->validate([
            'title' => 'sometimes|nullable|max:50',
            'content' => 'sometimes|nullable|max:500',
            'rating' => 'required|numeric|min:1|max:5',
        ]);

        auth()->user()->reviews()->create([
            'product_id' => $product->id,
            'title' => $request->title,
            'content' => $request->content,
            'rating' => $request->rating,
        ]);

        return $this->success([], 201);
    }
}
