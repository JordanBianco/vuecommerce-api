<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OrderController;

Route::prefix('orders')->group(function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::get('/archived', [OrderController::class, 'archivedIndex']);
    Route::get('/products-reviewed', [OrderController::class, 'productsReviewed']);
    Route::get('/purchased-products', [OrderController::class, 'purchasedProducts']);
    Route::get('/last', [OrderController::class, 'last']);


    Route::post('/', [OrderController::class, 'store']);

    Route::patch('/{order:id}/archive', [OrderController::class, 'archive']);
    Route::patch('/{order:id}/restore', [OrderController::class, 'restore']);
});