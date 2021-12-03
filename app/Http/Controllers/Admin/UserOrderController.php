<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\User;

class UserOrderController extends Controller
{
    public function index(User $user)
    {
        $search = request('search', '');
        $fstatus = request('fstatus', '');
        $sort = request('sort', 'created_at');
        $dir = request('dir', 'desc');
        $perPage = request('perPage', 5);

        return OrderResource::collection(
            Order::where('user_id', $user->id)
                ->withSearch($search)
                ->withFilterStatus($fstatus)
                ->orderBy($sort, $dir)
                ->paginate($perPage)
        );
    }
}
