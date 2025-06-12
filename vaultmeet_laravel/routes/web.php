<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Models\User;

use App\Http\Controllers\vaultmeetController;

Route::get('projekt/{any?}', function () {
    return file_get_contents(public_path('projekt/index.html'));
})->where('any', '.*');

Route::controller(vaultmeetController::class)->group(function () {

    //Route::post('api/register', 'register');

    Route::post('api/pass_update', 'pass_update');

    Route::post('api/login', 'login');

    Route::post('api/forgot_password', 'forgot_password');
    Route::post('api/chat', 'valasz');

});

Route::middleware('auth:sanctum')->group(function () {
     
   // Route::post('api/chat', [App\Http\Controllers\vaultmeetController::class, 'valasz']);
    Route::post('api/events', [App\Http\Controllers\vaultmeetController::class, 'events_store']);
    Route::get('api/events', [App\Http\Controllers\vaultmeetController::class, 'events_list']);
    Route::post('api/logout', [App\Http\Controllers\vaultmeetController::class, 'logout']);
    Route::middleware('auth:sanctum')->get('api/user', [vaultmeetController::class, 'user']);
    Route::delete('api/events/{id}', [vaultmeetController::class, 'delete_event']);
    Route::put('api/events/{id}', [vaultmeetController::class, 'update_event']);
});


