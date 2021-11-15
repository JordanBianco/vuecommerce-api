<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SavedProductController;

Route::prefix('saved')->group(function () {
    Route::get('/', [SavedProductController::class, 'index']);
    Route::post('/{product:id}', [SavedProductController::class, 'store']);
    Route::delete('/{product:id}', [SavedProductController::class, 'destroy']);
    Route::delete('/', [SavedProductController::class, 'destroyAll']);
});