<?php

use Illuminate\Support\Facades\Route;
use Modules\Wallets\App\Http\Controllers\Api\TokenWalletController;
use Modules\Wallets\App\Http\Controllers\Api\CommissionWalletController;
use Modules\Wallets\App\Http\Controllers\Api\CommissionWalletTransactionController;

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

Route::group(['middleware' => ['auth:api'], 'verified'], function () {
    Route::prefix('wallet')->group(function () {
        Route::get('commission', [CommissionWalletController::class, 'index']);
        Route::get('token', [TokenWalletController::class, 'index']);
        Route::apiResource('transactions', CommissionWalletTransactionController::class);
        Route::post('transactions/{id}', [CommissionWalletTransactionController::class, 'update']);
    });
});
