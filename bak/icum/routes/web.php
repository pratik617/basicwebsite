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

Route::get('/', function () {
    return view('welcome');
});

Route::get('403', function(){
return "403 error.";
})->name('403');

Route::get('404', function(){
return "Page not found.";
})->name('404');

Route::get('500', function(){
return "500 error.";
})->name('500');

Route::get('503', function(){
return "503 error.";
})->name('503');

// ->middleware('admin')
// START admin route
Route::prefix('admin')->as('admin.')->group(function() {
  Route::get('/','Admin\LoginController@showLoginForm')->name('login');
	Route::post('/login','Admin\LoginController@login')->name('login.submit');
  Route::post('/logout','Admin\LoginController@logout')->name('logout');
  Route::get('/dashboard', 'Admin\DashboardController@index')->name('dashboard');
  Route::get('/members/getData', 'Admin\MembersController@getData')->name('members.data');
  Route::resource('/members', 'Admin\MembersController');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
//Route::get('')
