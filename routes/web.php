<?php

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

Route::any('/', function () {
  return redirect('api');
});


//Auth::routes();


/*Route::name('country.')->group(function () {

    Route::get('/countries', 'CountryController@index')->name('index');
    Route::get('/countries/create', 'CountryController@create')->name('create');
    Route::post('/countries', 'CountryController@store')->name('store');
    Route::get('/countries/{country}/edit', 'CountryController@edit')->name('edit');
    Route::get('/countries/{country}', 'CountryController@show')->name('show');
    Route::put('/countries/{country}', 'CountryController@update')->name('update');
    Route::delete('/countries/{country}', 'CountryController@destroy')->name('delete');

});*/

