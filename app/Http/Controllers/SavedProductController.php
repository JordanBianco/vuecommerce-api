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
        $user = User::first();
        return SavedProductResource::collection(
            $user->products
        );
    }

    public function store(Product $product)
    {
        $user = User::first();

        $itemInCart = $user->products()->where('product_id', $product->id)->first();

        if ($itemInCart) {
            return;
        } else {
            $user->products()->attach($product->id);
        }
    }

    public function destroy(Product $product)
    {
        $user = User::first();
        $user->products()->detach($product->id);
    }

    public function destroyAll()
    {
        $user = User::first();
        $user->products()->detach();
    }
}
