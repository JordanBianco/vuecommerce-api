<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        // $cart = auth()->user()->cart; // quando avrò sanctum
        $cart = Cart::first();
        return CartResource::collection(
            $cart->products
        );
    }

    public function store(Request $request)
    {
        // $cart = auth()->user()->cart; // quando avrò sanctum
        $cart = Cart::first();
        
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
        
        return response()->json([
            'message' => 'success',
        ]);
    }

    public function increment(Product $product)
    {
        // $cart = auth()->user()->cart; // quando avrò sanctum
        $cart = Cart::first();
        
        $itemInCart = $cart->products()->where('product_id', $product->id)->first();

        $itemInCart->pivot->update([
            'quantity' => $itemInCart->pivot->quantity = $itemInCart->pivot->quantity + 1
        ]);
        
        return response()->json([
            'message' => 'success',
        ]);
    }

    public function decrement(Product $product)
    {
        // $cart = auth()->user()->cart; // quando avrò sanctum
        $cart = Cart::first();
        
        $itemInCart = $cart->products()->where('product_id', $product->id)->first();

        $itemInCart->pivot->update([
            'quantity' => $itemInCart->pivot->quantity = $itemInCart->pivot->quantity - 1
        ]);
        
        return response()->json([
            'message' => 'success',
        ]);
    }

    public function destroy(Product $product)
    {
        // $cart = auth()->user()->cart; // quando avrò sanctum
        $cart = Cart::first();
        $cart->products()->detach($product->id);

        return response()->json([
            'message' => 'success',
        ]);
    }
    
    public function destroyAll()
    {
        // $cart = auth()->user()->cart; // quando avrò sanctum
        $cart = Cart::first();
        $cart->products()->detach();

        return response()->json([
            'message' => 'success',
        ]);
    }
}
