<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ReviewController;

Route::get('/reviews', [ReviewController::class, 'index']);
Route::get('/reviews/last', [ReviewController::class, 'last']);
Route::post('/{product:id}/review', [ReviewController::class, 'store']);
Route::patch('/reviews/{review:id}', [ReviewController::class, 'update']);
Route::delete('/reviews/{review:id}', [ReviewController::class, 'destroy']);
