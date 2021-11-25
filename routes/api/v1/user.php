<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;

Route::prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::patch('/personal-info', [UserController::class, 'updateInfo']);
    Route::patch('/address', [UserController::class, 'updateAddress']);
});