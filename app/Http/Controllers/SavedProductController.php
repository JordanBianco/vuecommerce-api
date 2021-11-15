<?php

namespace App\Http\Controllers;

use App\Http\Resources\SavedProductResource;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class SavedProductController extends Controller
{
    public function index()
    {
        return SavedProductResource::collection(
            auth()->user()->products
        );
    }

    public function store(Product $product)
    {
        $user = auth()->user();

        $itemInCart = $user->products()->where('product_id', $product->id)->first();

        if ($itemInCart) {
            return;
        } else {
            $user->products()->attach($product->id);
        }
    }

    public function destroy(Product $product)
    {
        auth()->user()->products()->detach($product->id);
    }

    public function destroyAll()
    {
        auth()->user()->products()->detach();
    }
}
