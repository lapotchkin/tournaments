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


Route::get('/', 'Site\HomeController@index')->name('home');

/*
|--------------------------------------------------------------------------
| Social auth
|--------------------------------------------------------------------------
*/
Route::get('/social/{provider}', 'Auth\SocialController@redirect')
    ->name('social.redirect')
    ->where('provider', '[a-z]+');
Route::get('/social/{provider}/callback', 'Auth\SocialController@callback')
    ->where('provider', '[a-z]+');
Route::get('/logout', 'Auth\SocialController@logout')->name('logout');

/*
|--------------------------------------------------------------------------
| Tracker
|--------------------------------------------------------------------------
*/
Route::get('/tracker', 'Site\TrackerController@index')
    ->name('tracker');