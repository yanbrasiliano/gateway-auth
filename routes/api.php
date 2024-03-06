<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LDAPController;
use App\Http\Controllers\Auth\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    // Public routes (no authentication required)
    Route::get('/', function () {
        return response()->json(['message' => 'API ONLINE', 'env' => app()->environment()]);
    });

    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/ldap', [LDAPController::class, 'index']);

    // Routes protected by Sanctum middleware (auth:sanctum)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::post('/logout', [AuthController::class, 'logout']);
    });
});
