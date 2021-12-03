<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\UserOrderController;

Route::prefix('users')->group(function () {
    Route::get('/', [UsersController::class, 'index']);
    Route::get('/{user:id}', [UsersController::class, 'show']);
    Route::patch('/{user:id}/update', [UsersController::class, 'update']);
    Route::patch('/{user:id}/update-address', [UsersController::class, 'updateAddress']);
    Route::delete('/{user:id}/delete', [UsersController::class, 'destroy']);

    Route::get('/{user:id}/orders', [UserOrderController::class, 'index']);

});