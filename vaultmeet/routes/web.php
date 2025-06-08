<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\VaultmeetController;
use Illuminate\Http\Request; // Hozzáadva a Request importálása


Route::middleware('api')->group(function () {
    Route::post('/register', [VaultmeetController::class, 'register']);
    Route::post('/login', [VaultmeetController::class, 'login']);
    Route::post('/logout', [VaultmeetController::class, 'logout']);
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });
});

// Auth middleware nélkül elérhető user lekérdezés API útvonalon
Route::get('/api/user-public', function (Request $request) {
    return response()->json([
        'user' => $request->user(),
        'guest' => $request->user() === null,
    ]);
});