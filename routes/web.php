<?php

use App\Http\Controllers\GetAdsCityOrLocationController;
use Illuminate\Support\Facades\Route;

//CRAWLING ROUTES
Route::get('/halooglasi','App\Http\Controllers\AdControllers\AdController@getHalooglasiAds');
Route::get('/oglasi','App\Http\Controllers\AdControllers\AdController@getOglasiAds');
Route::get('mytest','App\Http\Controllers\AdControllers\AdController@myTest');


//Welcome/home page loader
Route::get('/', 'App\Http\Controllers\\AdControllers\AdController@welcomePage');


// we have this route: '/ad-list/non-processed/{savedFilterId?}'. Note the '?' sign. in the {savedFilterId?}
//the '?' means that the savedFilterId either will be there, or not. But in both cases, the NonProcessedAdController@index'
//will be called. We need these two options, because the NPads can be called either from a saved list (
//and on that case there will be a savedFilterId, or from the search term filter, in which case
//there will be no savedFilterId.

//Guests can see all ads, under the unprocessed url, but can't see the saved ad lists
Route::get('/ad-list/non-processed', 'App\Http\Controllers\AdControllers\NonProcessedAdController@index');

//gets all cities from db for the autocomplete
//Route::get('/cities','App\Http\Controllers\AdControllers\AdController@getCities');

//gets cities or locations for the autocomplete Vue component. The {type} here is either city or location.
Route::get('/autocomplete/ad/{type}', GetAdsCityOrLocationController::class);

//gets all locations from db for the autocomplete
//Route::get('/locations','App\Http\Controllers\AdControllers\AdController@getLocations');

//GROUP OF PROTECTED ROUTES
Route::middleware(['auth'])->group(function () {
	//NonProcessedAdController
	Route::get('/ad-list/non-processed/{savedFilterId?}', 'App\Http\Controllers\AdControllers\NonProcessedAdController@index');

    //StarredAdController
    Route::get('/ad-list/starred/{savedFilterId?}', 'App\Http\Controllers\AdControllers\StarredAdController@index');

    //DismissedAdController
    Route::get('/ad-list/dismissed/{savedFilterId?}', 'App\Http\Controllers\AdControllers\DismissedAdController@index');

    //sets if an ad is starred or dismissed
    Route::post('/set-dismissed','App\Http\Controllers\AdControllers\AdController@setDismissedInPivotTable');

	//sets an ad as a duplicate
	Route::post('/is-duplicate','App\Http\Controllers\AdControllers\AdController@isDuplicate');

	//sets an ad as a NOT duplicate
	Route::post('/not-duplicate','App\Http\Controllers\AdControllers\AdController@notDuplicate');

    //adList page / creating a new filter
    Route::post('/filters/create', 'App\Http\Controllers\FilterController@store');

	//adList page / will delete the relevant saved filter or list
	Route::delete('/ad-list/{savedFilterId}', 'App\Http\Controllers\FilterController@destroy');

    //adDetails page loader, will SHOW one ad detail page
    Route::get('/ads/{id}', 'App\Http\Controllers\AdControllers\AdController@showAd');
});

//DASHBOARD
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
