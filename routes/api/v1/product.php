<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{product:slug}', [ProductController::class, 'show']);    
    Route::get('/{product:slug}/similar', [ProductController::class, 'similar']);    
    Route::get('/{product:slug}/reviews', [ProductController::class, 'reviews']);
});