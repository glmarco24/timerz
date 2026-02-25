<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Routes in this file are automatically prefixed with '/api' and are
| assigned the 'api' middleware group via bootstrap/app.php.
|
*/

Route::get('/ping', function () {
    return response()->json(['message' => 'pongic']);
});

// Example placeholder for REST resources
// Route::apiResource('tasks', App\Http\Controllers\TaskController::class);

