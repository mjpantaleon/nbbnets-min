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

//LOGIN
Route::post('/login-attempt', 'LoginController@login');
Route::post('/android-submit-entry', 'PreScreenedDonorController@androidCreate');
Route::get('/get-user', 'LoginController@getUser');

Route::group(['middleware' => ['auth']], function () {

    // DONOR
    Route::get('/donor-list-data', 'DonorController@index');
    Route::get('/donor-profile/{id}', 'DonorController@donorDetails');
    Route::post('/get-searched-donor', 'DonorController@search');
    Route::post('/create-new-donor', 'DonorController@create');
    Route::post('/calculated-age', 'DonorController@getAge');

    // DONATION
    Route::post('/donation-list-data','DonationController@index');
    Route::post('/create-new-walkin','DonationController@create');

    Route::get('/mh-question-list', 'QuestionsController@getMH');
    Route::get('/pe-question-list', 'QuestionsController@getPE');

    // CANDIDATE DONORS
    Route::get('/pre-screened-donors', 'PreScreenedDonorController@index');
    Route::get('/pre-screened-donor/{id}', 'PreScreenedDonorController@getDetails');
    Route::post('/pre-screened-update/{id}', 'PreScreenedDonorController@update');
    Route::post('/submit-entry', 'PreScreenedDonorController@create');

    // VERIFIER

    Route::post('/verifier-credentials', 'VerifierController@getVerifier');
    
    // ---------------- BLOOD UNIT ------------------------ //

    //BLOOD TYPING
    Route::post('/get-donation-id', 'BloodTypingController@getDonationId');
    Route::post('/save-blood-typing', 'BloodTypingController@save');

    // Route::get('/getUserInfo', function () {
    //     return \Session::get('userInfo');
    // });

    Route::get('/logout', 'LoginController@logout');

});

// Route::get('/donor-id', 'DonorController@create');

Route::get('{any}', function () {
    return view('layouts.app');
})->where('any', '.*');

