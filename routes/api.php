<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SavedProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Products
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{product:slug}', [ProductController::class, 'show']);    
    Route::get('/{product:slug}/similar', [ProductController::class, 'similar']);    
    Route::get('/{product:slug}/reviews', [ReviewController::class, 'index']);    
});

// Categories
Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/{category:slug}/products', [CategoryController::class, 'show']);
});

// Cart
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index']);
    Route::post('/', [CartController::class, 'store']);
    Route::patch('/{product:id}/increment', [CartController::class, 'increment']);
    Route::patch('/{product:id}/decrement', [CartController::class, 'decrement']);
    Route::delete('/{product:id}', [CartController::class, 'destroy']);
    Route::delete('/', [CartController::class, 'destroyAll']);
});

// Saved Items
Route::prefix('saved')->group(function () {
    Route::get('/', [SavedProductController::class, 'index']);
    Route::post('/{product:id}', [SavedProductController::class, 'store']);
    Route::delete('/{product:id}', [SavedProductController::class, 'destroy']);
    Route::delete('/', [SavedProductController::class, 'destroyAll']);
});

// Orders -> testare
Route::post('/order', [OrderController::class, 'store']);