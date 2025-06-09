<?php

use Illuminate\Support\Facades\Route;



use App\Http\Controllers\gallery_usersController;

Route::get('projekt/{any?}', function () {
    return file_get_contents(public_path('projekt/index.html'));
})->where('any', '.*');

Route::controller(gallery_usersController::class)->group(function () {

    Route::post('api/register', 'register');

    Route::post('api/pass_update', 'pass_update');

    Route::post('api/login', 'login');

    Route::post('api/forgot_password', 'forgot_password');
});

Route::middleware('auth:sanctum')->group(function () {

    Route::post('api/logout', [App\Http\Controllers\gallery_usersController::class, 'logout']);
    Route::post('api/user', [App\Http\Controllers\gallery_usersController::class, 'user']);
});


