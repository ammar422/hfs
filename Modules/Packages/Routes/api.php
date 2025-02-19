<?php

use Illuminate\Support\Facades\Route;
use Modules\Packages\App\Http\Controllers\Api\PackageController;
use Modules\Packages\App\Http\Controllers\Api\SubscriptionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::apiResource('Packages', PackageController::class)->only('index', 'show');

Route::middleware('auth:api', 'verified')->group(function () {
    Route::prefix('user')->group(function () {
        Route::post('subscribe', [SubscriptionController::class, 'store'])->middleware('check.subscripition');
        Route::get('subscribe/{user_id}', [SubscriptionController::class, 'show']);
        Route::delete('subscribe/{user_id}', [SubscriptionController::class, 'destroy']);
    });
});
