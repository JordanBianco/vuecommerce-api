<?php

namespace App\Http\Controllers;

use App\Http\Requests\VerifyCouponRequest;
use App\Http\Resources\CouponResource;
use App\Models\Coupon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CouponController extends Controller
{
    public function verify(VerifyCouponRequest $request)
    {
        $this->isFirstPurchase($request->coupon);

        $coupon = Coupon::where(DB::raw('BINARY `code`'), $request->coupon)->first();

        if ($coupon) {
            return new CouponResource($coupon);
        } else {
            throw ValidationException::withMessages([
                'coupon' => ['Questo codice sconto non esiste']
            ]);
        }
    }

    // Controllo che sia il primo acquisto
    protected function isFirstPurchase($coupon)
    {
        if (auth()->user()->orders()->count() >= 1 && $coupon === 'FIRST-20') {
            throw ValidationException::withMessages([
                'coupon' => ['Questo sconto non è più valido']
            ]);
        }
    }
}
