<?php

/*
|--------------------------------------------------------------------------
| AJAX team routes
|--------------------------------------------------------------------------
|
| Here is where you can register AJAX web routes for teams management.
| These routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group.
|
*/
Route::put('/ajax/team', 'Ajax\TeamController@create')
    ->middleware('can:create,App\Models\Team');
Route::post('/ajax/team/{team}', 'Ajax\TeamController@edit')
    ->middleware('can:create,App\Models\Team');
Route::delete('/ajax/team/{team}', 'Ajax\TeamController@delete')
    ->middleware('can:create,App\Models\Team');;

Route::post('/ajax/team/{team}/app', 'Ajax\TeamController@setTeamId')
    ->middleware('can:create,App\Models\Team');
Route::delete('/ajax/team/{team}/app/{app}', 'Ajax\TeamController@deleteTeamId')
    ->middleware('can:create,App\Models\Team');

Route::put('/ajax/team/{team}', 'Ajax\TeamController@addPlayer')
    ->middleware('can:update,team');
Route::post('/ajax/team/{team}/{player}', 'Ajax\TeamController@updatePlayer')
    ->middleware('can:update,team');
Route::delete('/ajax/team/{team}/{player}', 'Ajax\TeamController@deletePlayer')
    ->middleware('can:update,team');
