<?php
use App\Http\Controllers\ComputerController;
use App\Http\Controllers\gallery_usersController;

Route::get('/computers', [ComputerController::class, 'list']);
Route::post('/computers', [ComputerController::class, 'upload']);
Route::delete('/computers/{id}', [ComputerController::class, 'delete']);

Route::post('register', [gallery_usersController::class, 'register']);
Route::post('pass_update', [gallery_usersController::class, 'pass_update']);
Route::post('login', [gallery_usersController::class, 'login']);
Route::post('forgot_password', [gallery_usersController::class, 'forgot_password']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [gallery_usersController::class, 'logout']);
    Route::post('user', [gallery_usersController::class, 'user']);
});
