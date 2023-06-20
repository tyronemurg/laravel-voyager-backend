<?php
use App\Models\Catalog;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/catalogs', 'CatalogController@catalogs');
Route::get('datatable/catalogs/getdata', 'CatalogController@getCatalogs')->name('data-catalogs');

    Route::get('/catalog/create', 'CatalogController@create')->name('add-catalog');

    Route::get('/catalogs/view/{id}', 'CatalogController@show')->name('show-catalogs');

    Route::post('/catalog/create/', 'CatalogController@store')->name('add-catalog');


    Route::post('/catalogs/{id}/edit/', 'CatalogController@update')->name('update-catalogs');

    Route::get('/catalog/{id}/edit/', 'CatalogController@edit')->name('update-catalog');



Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::get('pages', function() {
    return view('pages');
});
