<?php

use Modules\Ranks\Jobs\UpgradeRanks;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/rank', function () {
    UpgradeRanks::dispatch();
    return 'All users were inserted into the Ranked levels up';
});
