<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index()
    {
        $sort = request('sort', 'created_at.desc');

        return OrderResource::collection(
            Order::where('user_id', auth()->id())
                ->whereNull('archived_at')
                ->withSort($sort)
                ->get()
        );
    }

    public function archivedIndex()
    {
        $sort = request('sort', 'created_at.desc');

        return OrderResource::collection(
            Order::where('user_id', auth()->id())
                ->whereNotNull('archived_at')
                ->withSort($sort)
                ->get()
        );
    }
    
    public function show($slug)
    {
        $order = Order::where('order_number', $slug)->first();

        abort_if(!auth()->user()->orders->contains($order), 403);
        
        return new OrderResource($order);
    }

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

        return $this->success([
            'order' => new OrderResource($order)
        ]);
    }

    public function productsReviewed()
    {
        return auth()->user()->reviews->pluck('product_id');
    }

    public function purchasedProducts()
    {
        return auth()->user()->orders->load('products');
    }

    public function archive(Order $order)
    {
        $order->update([
            'archived_at' => now()
        ]);

        return $this->success([], 200);
    }

    public function restore(Order $order)
    {
        $order->update([
            'archived_at' => null
        ]);

        return $this->success([], 200);
    }
}
