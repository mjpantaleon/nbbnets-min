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
    Route::get('/donor-history/{id}', 'DonorController@donorHistory');
    Route::post('/get-searched-donor', 'DonorController@search');
    Route::post('/create-new-donor', 'DonorController@create');
    Route::post('/calculated-age', 'DonorController@getAge');
    
    // DONATION
    Route::post('/donation-list-data','DonationController@index');
    Route::post('/create-new-walkin','DonationController@create');
    
    // Route::get('/mh-question-list', 'QuestionsController@getMH');
    // Route::get('/pe-question-list', 'QuestionsController@getPE');
    
    // PRE-SCREENING
    Route::get('/pre-screened-donors', 'PreScreenedDonorController@index');
    Route::get('/pre-screened-donor/{id}', 'PreScreenedDonorController@getDetails');
    Route::post('/get-prescreened-donor', 'PreScreenedDonorController@search');
    Route::post('/pre-screened-update/{id}', 'PreScreenedDonorController@update');
    Route::post('/submit-entry', 'PreScreenedDonorController@create');
    // IGG TEST
    Route::post('/donor-list-for-igg', 'PreScreenedDonorController@getDonorsForIgg');
    Route::post('/save-igg-result', 'PreScreenedDonorController@saveIggResult');
    // HLA & HNA
    Route::post('/donor-list-for-hla-hna', 'PreScreenedDonorController@getDonorsForHlaHna');
    Route::post('/save-hla-hna-result', 'PreScreenedDonorController@saveHlaHnaResult');


    // FOR TESTING ENTRIES
    Route::get('/for-testing-list', 'PreScreenedDonorController@getList');
    Route::post('/get-approved-donor-list', 'PreScreenedDonorController@getApprovedDonorList');


    // VERIFIER

    Route::post('/verifier-credentials', 'VerifierController@getVerifier');
    
    // ---------------- BLOOD UNIT ------------------------ //
    // BLOOD TESTING
    Route::post('/get-donation-id-testing-details', 'TestingDetailsController@getDonationId');
    Route::post('/save-tti-blood-test', 'TestingDetailsController@save');
    // MIN
    Route::post('/save-blood-test-result/{id}', 'TestingDetailsController@addResult');

    //BLOOD TYPING
    Route::post('/get-donation-id', 'BloodTypingController@getDonationId');
    Route::post('/save-blood-typing', 'BloodTypingController@save');

    //BLOOD PROCESSING
    Route::post('/get-donation-id-processing', 'BloodProcessingController@getDonationId');
    Route::post('/save-blood-processing', 'BloodProcessingController@save');

    //BLOOD LABELLING
    Route::post('/get-donation-id-labelling', 'BloodLabellingController@getDonationId');
    Route::post('/save-blood-labelling', 'BloodLabellingController@save');

    // Route::get('/getUserInfo', function () {
    //     return \Session::get('userInfo');
    // });

    // ADDITIONAL BLOOD TEST
    // NAT
    Route::post('/components-for-nat-test', 'AdditionalTestController@getComponentsForNatTest');
    Route::post('/save-nat-result', 'AdditionalTestController@saveNatResult');
    // ZIKA
    Route::post('/components-for-zika-test', 'AdditionalTestController@getComponentsForZikaTest');
    Route::post('/save-zika-result', 'AdditionalTestController@saveZikaResult');
    // HNA & HNL
    // Route::post('/components-for-hna-test', 'AdditionalTestController@getComponentsForHnaTest');
    // Route::post('/save-hna-result', 'AdditionalTestController@saveHnaResult');




    //SHOW PREVIEW
    Route::get('/preview', 'PreviewController@showPreview');
    Route::get('/barcode/{donation_id}','PreviewController@barcode');

    //RELEASE TO INVENTORY
    Route::post('/get-donation-id-release', 'ReleaseInventoryController@getDonationId');
    Route::post('/update-blood-inventory', 'ReleaseInventoryController@update');

    //AVAILABLE BLOOD STOCKS
    Route::get('/get-available-blood-stocks', 'AvailableBloodStocksController@getList');
    Route::post('/update-available-list', 'AvailableBloodStocksController@updateList');
    
    //ALIQUOTE
    Route::post('/get-aliqoute-wb', 'AliquoteController@getAliquote');
    Route::post('/save-aliquote-wb', 'AliquoteController@saveAliquote');
    
    // BLOOD REQUEST
    Route::post('/blood-request-list', 'BloodRequestController@bloodRequestList');
    Route::get('/cp-components', 'BloodRequestController@getCpComponents');
    Route::get('/for-look-up/{id}', 'BloodRequestController@forLookUp');
    Route::post('/available-cp-components', 'BloodRequestController@getAvailableCpUnits');
    Route::post('/reserve-blood-units', 'BloodRequestController@reserveBloodUnits');
    // Route::get('/agency-list', 'BloodRequestController@getAgencies');
    Route::post('/blood-request', 'BloodRequestController@create');
    Route::get('/blood-request-details/{id}', 'BloodRequestController@getBloodRequestDetails');
    Route::post('/issue-blood-request/{id}', 'BloodRequestController@issueBloodRequest');
    // Route::post('/cancel-blood-request/{id}', 'BloodRequestController@cancelBloodRequest');
    Route::post('/cancel-blood-request', 'BloodRequestController@cancelBloodRequest');
    Route::get('/data-for-issuance/{id}', 'BloodRequestController@getDataForIssuance');
    Route::get('/issued-blood-units/{id}', 'BloodRequestController@issuedBloodUnits');


    Route::get('/logout', 'LoginController@logout');

});

// Route::get('/donor-id', 'DonorController@create');

// !!! REMEMBER TO PLACE THIS AT A CONTROLLER
Route::get('{any}', 'PageController@home')->where('any', '.*');

