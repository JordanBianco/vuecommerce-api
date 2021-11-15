<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoryController;

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/{category:slug}/products', [CategoryController::class, 'show']);
});