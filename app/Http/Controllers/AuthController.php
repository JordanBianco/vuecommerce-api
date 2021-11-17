<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return $this->success([], 201);
    }

    public function login(LoginRequest $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            return $this->success();
        }  else {
            throw ValidationException::withMessages([
                'email' => ['Le credenziali inserite non sono corrette']
            ]);
        }
    }

    public function logout()
    {
        Auth::guard('web')->logout();

        return response()->json(['message' => 'logout']);
    }
}
