<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function update(UpdatePasswordRequest $request)
    {
        auth()->user()->update([
            'password' => Hash::make($request->newPassword)
        ]);

        return $this->success();
    }
}
