<?php

use Illuminate\Support\Facades\Route;
use Modules\Ranks\App\Http\Controllers\Api\RanksController;

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

    Route::apiResource('ranks', RanksController::class)->only('index', 'show');
    Route::get('next/rank', [RanksController::class, 'nextRank']);
    Route::get('downlines/rank/details', [RanksController::class, 'dowmlineRanksDetails']);
});
