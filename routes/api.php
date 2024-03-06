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
    // Catch all undefined routes and return 404 error message in JSON format.
    Route::fallback(function () {
        abort(404, 'API resource not found.');
    });

    // Public routes (no authentication required)
    Route::get('/', function () {
        return response()->json(['message' => 'API ONLINE', 'env' => app()->environment()]);
    });

    Route::post('/login', [AuthController::class, 'login']);

    Route::prefix('ldap')->group(function () {
        Route::get('/', [LDAPController::class, 'index']);
        Route::get('/getByCPF', [LDAPController::class, 'getByCPF']);
    });


    // Routes protected by Sanctum middleware (auth:sanctum)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::post('/logout', [AuthController::class, 'logout']);
    });
});
