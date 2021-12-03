<?php

use Illuminate\Support\Facades\Route;

require __DIR__ . '/api/v1/auth.php';
require __DIR__ . '/api/v1/category.php';
require __DIR__ . '/api/v1/product.php';

// Richiamo il file che contiene tutte le rotte admin
require __DIR__ . '/api/v1/admin/routes.php';

Route::middleware(['auth:sanctum'])->group(function () {
    require __DIR__ . '/api/v1/user.php';
    require __DIR__ . '/api/v1/cart.php';
    require __DIR__ . '/api/v1/saved.php';
    require __DIR__ . '/api/v1/order.php';
    require __DIR__ . '/api/v1/coupon.php';
    require __DIR__ . '/api/v1/review.php';
    require __DIR__ . '/api/v1/password.php';
});
