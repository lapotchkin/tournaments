<?php

/*
|--------------------------------------------------------------------------
| AJAX player routes
|--------------------------------------------------------------------------
|
| Here is where you can register AJAX web routes for players management.
| These routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group.
|
*/

Route::put('/ajax/player', 'Ajax\PlayerController@create')
    ->middleware('can:create,App\Models\Player');
Route::post('/ajax/player/{player}', 'Ajax\PlayerController@edit')
    ->middleware('can:update,player');
Route::delete('/ajax/player/{player}', 'Ajax\PlayerController@delete')
    ->middleware('can:create,App\Models\Player');
