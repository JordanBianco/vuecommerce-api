<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;

class OrderController extends Controller
{
    public function store(OrderRequest $request)
    {
        $validated = $request->validated();

        $order = new Order();

        $order->order_number = uniqid('order_', true);
        $order->user_id = $validated['user_id'];
        $order->first_name = $validated['first_name'];
        $order->last_name = $validated['last_name'];
        $order->email = $validated['email'];
        $order->country = $validated['country'];
        $order->city = $validated['city'];
        $order->province = $validated['province'];
        $order->address = $validated['address'];
        $order->zipcode = $validated['zipcode'];
        $order->phone = $validated['phone'];
        $order->notes = $validated['notes'];
        $order->total = $validated['total'];
        
        $order->save();

        foreach ($request->items as $item) {
            $order->products()->attach(
                $order->id,
                [
                    'product_id' => $item['product']['id'],
                    'quantity' => $item['quantity'],
                ]
            );
        }

        return response()->json([
            'message' => 'success',
            'order' => new OrderResource($order)
        ]);
    }
}
