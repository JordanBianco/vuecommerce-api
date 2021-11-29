<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Mail\newOrder;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index()
    {
        $search = request('search', '');
        $fstatus = request('fstatus', '');
        $sort = request('sort', 'created_at');
        $dir = request('dir', 'desc');
        $perPage = request('perPage', 5);

        return OrderResource::collection(
            Order::where('user_id', auth()->id())
                ->whereNull('archived_at')
                ->withSearch($search)
                ->withFilterStatus($fstatus)
                ->orderBy($sort, $dir)
                ->paginate($perPage)
        );
    }

    public function archivedIndex()
    {
        $search = request('search', '');
        $fstatus = request('fstatus', '');
        $sort = request('sort', 'created_at');
        $dir = request('dir', 'desc');
        $perPage = request('perPage', 5);

        return OrderResource::collection(
            Order::where('user_id', auth()->id())
                ->whereNotNull('archived_at')
                ->withSearch($search)
                ->withFilterStatus($fstatus)
                ->orderBy($sort, $dir)
                ->paginate($perPage)
        );
    }
    
    public function store(OrderRequest $request)
    {
        $order = auth()->user()->orders()->create([
            'order_number' => uniqid('order_', true),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'country' => $request->country,
            'city' => $request->city,
            'province' => $request->province,
            'address' => $request->address,
            'zipcode' => $request->zipcode,
            'phone' => $request->phone,
            'notes' => $request->notes,
            'total' => $request->total,
        ]);

        foreach ($request->items as $item) {
            $order->products()-> attach(
                $order->id,
                [
                    'product_id' => $item['product']['id'],
                    'quantity' => $item['quantity'],
                ]
            );
        }

        Mail::to($request->email)->send(new newOrder($order));

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

    public function last()
    {
        return Order::where('user_id', auth()->id())
                    ->orderBy('created_at', 'desc')
                    ->with('products')
                    ->first();
    }
}
