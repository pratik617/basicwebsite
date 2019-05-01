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
Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');
Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

// START projects routes
Route::get('projects/getData', 'ProjectController@getData')->name('projects.data');
Route::resource('projects', 'ProjectController');
//Route::POST('projects/{id}/print', 'ProjectController@print')->name('projects.print');
Route::POST('projects/print', 'ProjectController@print')->name('projects.print');
//Route::GET('projects/print', 'ProjectController@print')->name('projects.print');
// END projects routes


// START photos routes
Route::get('projects/{id}/photos', 'PhotoController@index')->name('photos.index');
Route::get('projects/{id}/photos/create', 'PhotoController@create')->name('photos.create');
Route::post('projects/{project}/photos', 'PhotoController@store')->name('photos.store');
Route::match(['put', 'patch'], 'photos/{id}', 'PhotoController@update')->name('photos.update');
Route::DELETE('photos', 'PhotoController@destroy')->name('photos.destroy');
Route::post('photos/order', 'PhotoController@storeOrder')->name('photos.order.store');
// END photos routes

Auth::routes();
