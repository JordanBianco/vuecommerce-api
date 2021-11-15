<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return CartResource::collection(
            auth()->user()->cart->products
        );
    }

    public function store(Request $request)
    {
        $cart = auth()->user()->cart;
        
        $itemInCart = $cart->products()->where('product_id', $request->product_id)->first();

        if (! $itemInCart) {
            $cart->products()->attach(
                $request->product_id, 
                ['quantity' => $request->quantity]
            );
        } else {
            $itemInCart->pivot->update([
                'quantity' => $itemInCart->pivot->quantity + $request->quantity
            ]);
        }
        
        return $this->success();
    }

    public function increment(Product $product)
    {
        $cart = auth()->user()->cart;
        
        $itemInCart = $cart->products()->where('product_id', $product->id)->first();

        $itemInCart->pivot->update([
            'quantity' => $itemInCart->pivot->quantity = $itemInCart->pivot->quantity + 1
        ]);
        
        return $this->success();
    }

    public function decrement(Product $product)
    {
        $cart = auth()->user()->cart;
        
        $itemInCart = $cart->products()->where('product_id', $product->id)->first();

        $itemInCart->pivot->update([
            'quantity' => $itemInCart->pivot->quantity = $itemInCart->pivot->quantity - 1
        ]);
        
        return $this->success();
    }

    public function destroy(Product $product)
    {
        auth()->user()->cart->products()->detach($product->id);

        return $this->success();

    }
    
    public function destroyAll()
    {
        auth()->user()->cart->products()->detach();

        return $this->success();

    }
}
