<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Models\User;

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
        Route::post('api/events', [App\Http\Controllers\gallery_usersController::class, 'events_store']);
    Route::get('api/events', [App\Http\Controllers\gallery_usersController::class, 'events_list']);
    Route::post('api/logout', [App\Http\Controllers\gallery_usersController::class, 'logout']);
    Route::middleware('auth:sanctum')->get('api/user', [gallery_usersController::class, 'user']);
    Route::delete('api/events/{id}', [gallery_usersController::class, 'delete_event']);
    Route::put('api/events/{id}', [gallery_usersController::class, 'update_event']);
});


