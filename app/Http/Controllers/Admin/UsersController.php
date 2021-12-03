<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserAddressRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class UsersController extends Controller
{
    public function index()
    {
        $search = request('search', '');
        $email_verified = request('email_verified', '');
        $sort = request('sort', 'id');
        $dir = request('dir', 'asc');
        $perPage = request('perPage', 10);

        return UserResource::collection(
            User::withSearch($search)
                ->withEmailVerified($email_verified)
                ->orderBy($sort, $dir)
                ->paginate($perPage)
        );
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $updated = $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
        ]);

        if (!$updated) {
            return $this->error();
        } else {
            return $this->success();
        }
    }

    public function updateAddress(UpdateUserAddressRequest $request, User $user)
    {
        $updated = $user->update([
            'country' => $request->country,
            'city' => $request->city,
            'province' => $request->province,
            'address' => $request->address,
            'zipcode' => $request->zipcode,
            'phone' => $request->phone
        ]);

        if (!$updated) {
            return $this->error();
        } else {
            return $this->success();
        }
    }

    public function destroy(User $user)
    {
        $user->delete();

        return $this->success();
    }
}
