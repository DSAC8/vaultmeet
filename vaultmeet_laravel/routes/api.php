<?php
use App\Http\Controllers\ComputerController;
use App\Http\Controllers\vaultmeetController;

Route::get('/computers', [ComputerController::class, 'list']);
Route::post('/computers', [ComputerController::class, 'upload']);
Route::delete('/computers/{id}', [ComputerController::class, 'delete']);

Route::post('register', [vaultmeetController::class, 'register']);
Route::post('pass_update', [vaultmeetController::class, 'pass_update']);
Route::post('login', [vaultmeetController::class, 'login']);
Route::post('forgot_password', [vaultmeetController::class, 'forgot_password']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [vaultmeetController::class, 'logout']);
    Route::post('user', [vaultmeetController::class, 'user']);
});
