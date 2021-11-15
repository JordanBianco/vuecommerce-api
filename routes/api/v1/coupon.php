<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CouponController;

Route::get('/coupons/verify', [CouponController::class, 'verify']);