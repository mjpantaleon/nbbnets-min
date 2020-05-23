<?php

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
// Route::get('/', function () {
//     return view('layouts.app');
// });

// Route::get('/home', 'HomeController@index')->name('home');
    
Auth::routes();

// DONOR
Route::get('/donor-list-data', 'DonorController@index');
Route::get('/donor-profile/{id}', 'DonorController@donorDetails');
Route::post('/get-searched-donor', 'DonorController@search');
Route::post('/create-new-donor', 'DonorController@create');

// DONATION
Route::post('/donation-list-data','DonationController@index');
Route::post('/create-new-walkin','DonationController@create');

// Route::get('/donor-id', 'DonorController@create');

// ROUTES FOR AUTHENTICATED USERS
// Route::group( ['middleware' => 'auth'], function() {

// }

Route::get('{any}', function () {
    return view('layouts.app');
})->where('any', '.*');

