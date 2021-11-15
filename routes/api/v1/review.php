<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ReviewController;

Route::post('/{product:id}/review', [ReviewController::class, 'store']);