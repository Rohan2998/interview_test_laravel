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
//     return view('welcome');
// });

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/product/get', 'HomeController@getProducts')->name('getProduct');
    Route::post('/product/store', 'HomeController@store')->name('storeProduct');
    Route::get('/product/edit/{id}', 'HomeController@edit')->name('editProduct');
    Route::post('/product/update/{id}', 'HomeController@update')->name('updateProduct');
    Route::get('/product/delete/multiple/{ids}', 'HomeController@destroyMultiple')->name('destroyMultiple');
    Route::get('/product/delete/{id}', 'HomeController@destroy')->name('deleteProduct');
});