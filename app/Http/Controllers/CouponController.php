<?php

namespace App\Http\Controllers;

use App\Http\Resources\CouponResource;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CouponController extends Controller
{
    public function verify(Request $request)
    {
        $request->validate([
            'coupon' => 'required'
        ]);

        if (auth()->user()->orders()->count() >= 1 && $request->coupon === 'FIRST-20') {
            throw ValidationException::withMessages([
                'coupon' => ['Questo sconto non è più valido']
            ]);
        }

        $coupon = Coupon::where(DB::raw('BINARY `code`'), $request->coupon)->first();

        if ($coupon) {
            return new CouponResource($coupon);
        } else {
            throw ValidationException::withMessages([
                'coupon' => ['Questo codice sconto non esiste']
            ]);
        }
    }
}
