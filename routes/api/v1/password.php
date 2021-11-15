<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PasswordController;

Route::patch('/password/change', [PasswordController::class, 'update']);