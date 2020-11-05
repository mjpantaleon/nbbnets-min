<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

#Route::get('')

Route::post('authorization', "ApiController@authorization");

// Route::get('v1/test', function(){
// 	return response()->json(['user' => 'testuser']);
// })->middleware('client');

Route::post('v1/android-login', 'ApiController@login')->middleware('client');
Route::post('v1/android-search-donors', 'ApiController@searchDonor')->middleware('client');
Route::post('v1/android-submit-entry', 'ApiController@androidCreate')->middleware('client');
Route::get('v1/android-donors', 'ApiController@getAllDonors')->middleware('client');
Route::get('v1/android-version', 'ApiController@version')->middleware('client');
 