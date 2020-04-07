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
Route::put('/ajax/personal', 'Ajax\PersonalController@create');
Route::post('/ajax/personal/{tournamentId}', 'Ajax\PersonalController@edit')
    ->where(['tournamentId' => '[0-9]+']);
Route::delete('/ajax/personal/{tournamentId}', 'Ajax\PersonalController@delete')
    ->where(['tournamentId' => '[0-9]+']);
Route::post('/ajax/personal/{tournamentId}/winner', 'Ajax\PersonalController@setWinner')
    ->where(['tournamentId' => '[0-9]+']);

Route::put('/ajax/personal/{tournamentId}/player', 'Ajax\PersonalController@addPlayer')
    ->where(['tournamentId' => '[0-9]+']);
Route::post('/ajax/personal/{tournamentId}/player/{playerId}', 'Ajax\PersonalController@editPlayer')
    ->where(['tournamentId' => '[0-9]+', 'playerId' => '[0-9]+']);
Route::delete('/ajax/personal/{tournamentId}/player/{playerId}', 'Ajax\PersonalController@deletePlayer')
    ->where(['tournamentId' => '[0-9]+', 'playerId' => '[0-9]+']);

/*
|--------------------------------------------------------------------------
| Regular
|--------------------------------------------------------------------------
*/
Route::post('/ajax/personal/{tournamentId}/regular/{gameId}', 'Ajax\PersonalController@editRegularGame')
    ->where(['tournamentId' => '[0-9]+', 'gameId' => '[0-9]+']);

/*
|--------------------------------------------------------------------------
| Playoff
|--------------------------------------------------------------------------
*/
Route::put('/ajax/personal/{tournamentId}/playoff', 'Ajax\PersonalController@createPair')
    ->where(['tournamentId' => '[0-9]+']);
Route::post('/ajax/personal/{tournamentId}/playoff/{pairId}', 'Ajax\PersonalController@updatePair')
    ->where(['tournamentId' => '[0-9]+', 'pairId' => '[0-9]+']);
Route::put('/ajax/personal/{tournamentId}/playoff/{pairId}', 'Ajax\PersonalController@createPlayoffGame')
    ->where(['tournamentId' => '[0-9]+', 'pairId' => '[0-9]+']);
Route::post('/ajax/personal/{tournamentId}/playoff/{pairId}/{gameId}', 'Ajax\PersonalController@editPlayoffGame')
    ->where(['tournamentId' => '[0-9]+', 'pairId' => '[0-9]+', 'gameId' => '[0-9]+']);

/*
|--------------------------------------------------------------------------
| VK
|--------------------------------------------------------------------------
*/
Route::post('/ajax/personal/{tournamentId}/regular/{gameId}/share', 'Ajax\PersonalController@shareRegularResult')
    ->where(['tournamentId' => '[0-9]+', 'gameId' => '[0-9]+']);
Route::post(
    '/ajax/personal/{tournamentId}/playoff/{pairId}/{gameId}/share',
    'Ajax\PersonalController@sharePlayoffResult'
)
    ->where(['tournamentId' => '[0-9]+', 'pairId' => '[0-9]+', 'gameId' => '[0-9]+']);
