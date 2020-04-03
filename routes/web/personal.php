<?php

/*
|--------------------------------------------------------------------------
| Common
|--------------------------------------------------------------------------
*/
Route::get('/personal', 'Site\PersonalController@index')
    ->name('personal');
Route::get('/personal/new', 'Site\PersonalController@new')
    ->name('personal.new');
Route::get('/personal/{tournamentId}', 'Site\PersonalController@players')
    ->where(['tournamentId' => '[0-9]+'])
    ->name('personal.tournament');
Route::get('/personal/{tournamentId}/edit', 'Site\PersonalController@edit')
    ->where(['tournamentId' => '[0-9]+'])
    ->name('personal.tournament.edit');
Route::get('/personal/{tournamentId}/player/{playerId}', 'Site\PersonalController@player')
    ->where(['tournamentId' => '[0-9]+', 'playerId' => '[0-9]+'])
    ->name('personal.tournament.player');
Route::get('/personal/{tournamentId}/map', 'Site\PersonalController@map')
    ->where(['tournamentId' => '[0-9]+'])
    ->name('personal.tournament.map');
Route::get('/personal/{tournamentId}/copypaste', 'Site\PersonalController@players')
    ->where(['tournamentId' => '[0-9]+'])
    ->name('personal.tournament.copypaste');

/*
|--------------------------------------------------------------------------
| Regular
|--------------------------------------------------------------------------
*/
Route::get('/personal/{tournamentId}/regular', 'Site\PersonalRegularController@index')
    ->where(['tournamentId' => '[0-9]+'])
    ->name('personal.tournament.regular');
Route::get('/personal/{tournamentId}/regular/games', 'Site\PersonalRegularController@games')
    ->where(['tournamentId' => '[0-9]+'])
    ->name('personal.tournament.regular.games');
Route::get('/personal/{tournamentId}/regular/games/{gameId}', 'Site\PersonalRegularController@game')
    ->where(['tournamentId' => '[0-9]+', 'gameId' => '[0-9]+'])
    ->name('personal.tournament.regular.game');
Route::get('/personal/{tournamentId}/regular/games/{gameId}/edit', 'Site\PersonalRegularController@gameEdit')
    ->where(['tournamentId' => '[0-9]+', 'gameId' => '[0-9]+'])
    ->name('personal.tournament.regular.game.edit');
Route::get('/personal/{tournamentId}/regular/schedule', 'Site\PersonalRegularController@schedule')
    ->where(['tournamentId' => '[0-9]+', 'gameId' => '[0-9]+'])
    ->name('personal.tournament.regular.schedule');

/*
|--------------------------------------------------------------------------
| Playoff
|--------------------------------------------------------------------------
*/
Route::get('/personal/{tournamentId}/playoff', 'Site\PersonalPlayoffController@index')
    ->where(['tournamentId' => '[0-9]+'])
    ->name('personal.tournament.playoff');
Route::get('/personal/{tournamentId}/playoff/games', 'Site\PersonalPlayoffController@index')
    ->where(['tournamentId' => '[0-9]+'])
    ->name('personal.tournament.playoff.games');
Route::get('/personal/{tournamentId}/playoff/games/{pairId}/add', 'Site\PersonalPlayoffController@gameAdd')
    ->where(['tournamentId' => '[0-9]+', 'pairId' => '[0-9]+'])
    ->name('personal.tournament.playoff.game.add');
Route::get('/personal/{tournamentId}/playoff/games/{pairId}/{gameId}', 'Site\PersonalPlayoffController@game')
    ->where(['tournamentId' => '[0-9]+', 'pairId' => '[0-9]+', 'gameId' => '[0-9]+'])
    ->name('personal.tournament.playoff.game');
Route::get('/personal/{tournamentId}/playoff/games/{pairId}/{gameId}/edit', 'Site\PersonalPlayoffController@gameEdit')
    ->where(['tournamentId' => '[0-9]+', 'pairId' => '[0-9]+', 'gameId' => '[0-9]+'])
    ->name('personal.tournament.playoff.game.edit');