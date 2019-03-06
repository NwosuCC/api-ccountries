<?php

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

Route::any('/', function (){
  return redirect('/api/v1');
});


Route::group(['prefix' => 'v1'], function() {

  Route::namespace('Auth')->group(function () {
    Route::post('/register', 'RegisterController@register');
    Route::post('/login', 'LoginController@login');
  });


  Route::any('/', function () {
    return redirect('/api/v1/countries');
  });


  Route::middleware(['auth:api'])->group(function () {

    // Countries
    Route::get('/countries', 'CountryController@index');
    Route::get('/countries/create', 'CountryController@create');
    Route::post('/countries', 'CountryController@store')->middleware('continent_title');
    Route::get('/countries/{country}/edit', 'CountryController@edit');
    Route::get('/countries/{country}', 'CountryController@show');
    Route::put('/countries/{country}', 'CountryController@update')->middleware('continent_title');
    Route::delete('/countries/{country}', 'CountryController@destroy');


    // Activities
    Route::get('/activities', 'AuditController@index')->middleware('can:create,App\Audit');

  });

});
