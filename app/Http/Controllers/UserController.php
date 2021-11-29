<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserAddressRequest;
use App\Http\Requests\UpdateUserInfoRequest;
use App\Http\Resources\ActivityResource;
use App\Models\Activity;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->loadCount('orders', 'reviews');
    }

    public function activities()
    {
        return ActivityResource::collection(
            Activity::where('user_id', auth()->id())
                ->with('subject')
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get()
        );
    }

    public function updateInfo(UpdateUserInfoRequest $request)
    {
        auth()->user()->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
        ]);

        return $this->success();
    }

    public function updateAddress(UpdateUserAddressRequest $request)
    {
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

    public function destroy()
    {
        auth()->user()->delete();

        return $this->success();
    }
}
