<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Wallets\Entities\CommissionWallet;
use Modules\Commissions\App\Http\Controllers\Api\CommissionController;

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

Route::middleware('auth:api', 'verified')->group(function () {
    Route::prefix('user')->group(function () {
        Route::apiResource('commission', CommissionController::class)->only('store');
    });
});
