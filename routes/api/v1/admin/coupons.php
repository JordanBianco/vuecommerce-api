<?php

use App\Http\Controllers\CouponController;
use Illuminate\Support\Facades\Route;

Route::prefix('coupons')->group(function () {
    Route::get('/', [CouponController::class, 'index']);
    Route::get('/{coupon:id}', [CouponController::class, 'show']);
    Route::post('/', [CouponController::class, 'store']);
    Route::patch('/{coupon:id}/update', [CouponController::class, 'update']);
    Route::delete('/{coupon:id}/delete', [CouponController::class, 'destroy']);
});