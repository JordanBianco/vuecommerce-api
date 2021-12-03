<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->middleware(['auth:sanctum', 'admin'])
    ->group(function () {
        require __DIR__ . '/users.php';
        require __DIR__ . '/coupons.php';
});