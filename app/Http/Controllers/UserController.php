<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReviewResource;
use App\Models\Review;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->loadCount('orders');
    }

    public function updateInfo(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
        ]);

        auth()->user()->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
        ]);

        return $this->success();
    }

    public function updateAddress(Request $request)
    {
        $request->validate([
            'country' => 'required',
            'city' => 'required',
            'province' => 'required',
            'address' => 'required',
            'zipcode' => 'required|regex:/^[0-9]{3,7}$/',
            'phone' => 'required|numeric'
        ]);

        auth()->user()->update([
            'country' => $request->country,
            'city' => $request->city,
            'province' => $request->province,
            'address' => $request->address,
            'zipcode' => $request->zipcode,
            'phone' => $request->phone,
        ]);

        return $this->success();
    }

    public function reviews()
    {
        $sort = request('sort', 'created_at.desc');

        return ReviewResource::collection(
            Review::where('user_id', auth()->id())
                ->withSort($sort)
                ->with(['product', 'user:id,first_name,last_name'])
                ->get()
        );
    }
}
