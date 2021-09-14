<?php

/*
|--------------------------------------------------------------------------
| AJAX personal tournaments routes
|--------------------------------------------------------------------------
|
| Here is where you can register AJAX web routes for personal tournaments.
| These routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group.
|
*/

/*
|--------------------------------------------------------------------------
| Common
|--------------------------------------------------------------------------
*/
Route::put('/ajax/personal', 'Ajax\PersonalController@create')
    ->middleware('can:create,App\Models\PersonalTournament');
Route::post('/ajax/personal/{personalTournament}', 'Ajax\PersonalController@edit')
    ->middleware('can:create,App\Models\PersonalTournament');
Route::delete('/ajax/personal/{personalTournament}', 'Ajax\PersonalController@delete')
    ->middleware('can:create,App\Models\PersonalTournament');

Route::post('/ajax/personal/{personalTournament}/winner', 'Ajax\PersonalController@setWinner')
    ->middleware('can:create,App\Models\PersonalTournament');

Route::put('/ajax/personal/{personalTournament}/player', 'Ajax\PersonalController@addPlayer')
    ->middleware('can:create,App\Models\PersonalTournament');
Route::post('/ajax/personal/{personalTournament}/player/{player}', 'Ajax\PersonalController@editPlayer')
    ->middleware('can:create,App\Models\PersonalTournament');
Route::delete('/ajax/personal/{personalTournament}/player/{player}', 'Ajax\PersonalController@deletePlayer')
    ->middleware('can:create,App\Models\PersonalTournament');

Route::put('/ajax/personal/{personalTournament}/schedule', 'Ajax\PersonalController@addSchedule')
    ->middleware('can:create,App\Models\PersonalTournament');
Route::delete('/ajax/personal/{personalTournament}/schedule', 'Ajax\PersonalController@deleteSchedule')
    ->middleware('can:create,App\Models\PersonalTournament');

/*
|--------------------------------------------------------------------------
| Regular
|--------------------------------------------------------------------------
*/
Route::post('/ajax/personal/{personalTournament}/regular/{personalGameRegular}', 'Ajax\PersonalController@editRegularGame')
    ->middleware('can:update,personalGameRegular');

/*
|--------------------------------------------------------------------------
| Playoff
|--------------------------------------------------------------------------
*/
Route::put('/ajax/personal/{personalTournament}/playoff', 'Ajax\PersonalController@createPair')
    ->middleware('can:create,App\Models\PersonalTournament');
Route::post(
    '/ajax/personal/{personalTournament}/playoff/{personalTournamentPlayoff}',
    'Ajax\PersonalController@updatePair'
)
    ->middleware('can:create,App\Models\PersonalTournament');
Route::put(
    '/ajax/personal/{personalTournament}/playoff/{personalTournamentPlayoff}',
    'Ajax\PersonalController@createPlayoffGame'
)
    ->middleware('can:update,personalTournamentPlayoff');
Route::post('/ajax/personal/{personalTournament}/playoff/{personalTournamentPlayoff}/{personalGamePlayoff}', 'Ajax\PersonalController@editPlayoffGame')
    ->middleware('can:update,personalTournamentPlayoff');

/*
|--------------------------------------------------------------------------
| VK
|--------------------------------------------------------------------------
*/
Route::post('/ajax/personal/{personalTournament}/regular/{personalGameRegular}/share', 'Ajax\PersonalController@shareRegularResult')
    ->middleware('can:create,App\Models\PersonalTournament');
Route::post(
    '/ajax/personal/{personalTournament}/playoff/{personalTournamentPlayoff}/{personalGamePlayoff}/share',
    'Ajax\PersonalController@sharePlayoffResult'
)
    ->middleware('can:create,App\Models\PersonalTournament');
