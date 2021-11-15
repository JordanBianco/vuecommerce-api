<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'oldPassword' => 'required|current_password',
            'newPassword' => [
                        'required',
                        'confirmed',
                        Password::min(8)
                            ->numbers()
                            ->symbols()
            ]
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->newPassword)
        ]);

        return $this->success();
    }
}
