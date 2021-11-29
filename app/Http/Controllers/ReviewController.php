<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Activity;
use App\Models\Product;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        $search = request('search', '');
        $sort = request('sort', 'created_at');
        $dir = request('dir', 'desc');
        $perPage = request('perPage', 5);

        return ReviewResource::collection(
            Review::where('user_id', auth()->id())
                ->withSearch($search)
                ->orderBy($sort, $dir)
                ->with(['product', 'user:id,first_name,last_name'])
                ->paginate($perPage)
        );
    }

    public function store(Product $product, StoreReviewRequest $request)
    {
        auth()->user()->reviews()->create([
            'product_id' => $product->id,
            'content' => $request->content,
            'rating' => $request->rating,
        ]);

        return $this->success([], 201);
    }

    public function update(Review $review, UpdateReviewRequest $request)
    {
        abort_if(!auth()->user()->reviews->contains($review), 403);

        $review->update([
            'content' => $request->content,
            'rating' => $request->rating,
        ]);

        return $this->success();
    }

    public function last()
    {
        return Review::where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->with('product')
                ->first();
    }

    public function destroy(Review $review)
    {
        abort_if(!auth()->user()->reviews->contains($review), 403);

        $review->delete();

        return $this->success();
    }
}
