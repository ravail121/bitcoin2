<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('deposit/new', 'IpnController@index');
Route::post('deposit/block', 'IpnController@block');
Route::post('/isWalletAdressExitsInSystem', 'IpnController@isWalletAdressExitsInSystem')->name('ipn.isWalletAdressExitsInSystem');
Route::post('/calculateRelativeAmount', 'IpnController@calculateRelativeAmount')->name('ipn.calculateRelativeAmount');
Route::get('/addWaterMark', 'IpnController@addWaterMark')->name('ipn.addWaterMark');
Route::get('/trimUserLogin/{start}/{end}', 'IpnController@trimUserLogin')->name('ipn.trimUserLogin');

// Route::group(['middleware' => 'admin'], function () {
    // Route::get('/getDashboardStats', 'IpnController@getDashboardStats')->name('ipn.getDashboardStats');
// });
