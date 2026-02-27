<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TimeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Routes in this file are automatically prefixed with '/api' and are
| assigned the 'api' middleware group via bootstrap/app.php.
|
*/

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/me', [AuthController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Time-related endpoints
    Route::get('/time', [TimeController::class, 'index']);

});
