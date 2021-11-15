<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|alpha|max:50',
            'last_name' => 'required|alpha|max:100',
            'email' => 'required|email|max:255',
            'password' => [
                'required', 
                Password::min(8)
                    ->numbers()
                    ->symbols()
            ]
        ]);

        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return $this->success([], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

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
