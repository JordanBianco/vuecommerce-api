<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CartController;

Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index']);
    Route::post('/', [CartController::class, 'store']);
    Route::patch('/{product:id}/increment', [CartController::class, 'increment']);
    Route::patch('/{product:id}/decrement', [CartController::class, 'decrement']);
    Route::delete('/{product:id}', [CartController::class, 'destroy']);
    Route::delete('/', [CartController::class, 'destroyAll']);
});