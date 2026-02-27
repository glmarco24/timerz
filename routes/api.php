<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\TimesController;

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

    Route::get('/companies', [CompanyController::class, 'index']);
    Route::get('/companies/{company}/staff', [MembershipController::class, 'index']);
    Route::get('/times', [TimesController::class, 'index']);
    Route::post('/times', [TimesController::class, 'store']);
    Route::put('/times/{time}', [TimesController::class, 'update']);

});
