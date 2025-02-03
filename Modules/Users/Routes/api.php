<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckAccountStatus;
use Modules\Users\App\Http\Controllers\UsersAuthController;
use Modules\Users\App\Http\Controllers\ForgotPasswordController;


Route::group(['middleware' => ['guest:api']], function () {
    Route::post('login', [UsersAuthController::class, 'login']);
    Route::post('register', [UsersAuthController::class, 'register']);

    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetCode']);
    Route::post('reset-password', [ForgotPasswordController::class, 'resetPassword']);
});


Route::group(['middleware' => ['auth:api']], function () {
    Route::post('verify-email', [UsersAuthController::class, 'verifyEmail'])->withoutMiddleware([CheckAccountStatus::class]);
});

Route::group(['middleware' => ['auth:api'], 'verified'], function () {
    Route::post('logout', [UsersAuthController::class, 'logout']);
    Route::post('refresh', [UsersAuthController::class, 'refresh']);
    Route::get('me', [UsersAuthController::class, 'me']);
    Route::post('edit/profile' , [UsersAuthController::class , 'editProfile']);
});
