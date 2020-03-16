<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;

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

Route::prefix('api')->group(function () {

    Route::get('/exchange-rates', ['uses' => 'ApiController@getExchangeRates']);
    Route::get('/exchange-rates/history/', ['uses' => 'ApiController@getHistory']);
});