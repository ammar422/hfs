<?php

use Illuminate\Support\Facades\Route;
use Modules\Wallets\App\Http\Controllers\Api\TokenWalletController;
use Modules\Wallets\App\Http\Controllers\Api\CommissionWalletController;

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

    Route::get('wallet/commission', [CommissionWalletController::class, 'index']);

    Route::get('wallet/token', [TokenWalletController::class, 'index']);
});
