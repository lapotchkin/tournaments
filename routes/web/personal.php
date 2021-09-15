<?php

/*
|--------------------------------------------------------------------------
| Common
|--------------------------------------------------------------------------
*/
Route::get('/personal', 'Site\PersonalController@index')
    ->name('personal');
Route::get('/personal/new', 'Site\PersonalController@create')
    ->middleware('can:create,App\Models\PersonalTournament')
    ->name('personal.new');
Route::get('/personal/{personalTournament}', 'Site\PersonalController@players')
    ->name('personal.tournament');
Route::get('/personal/{personalTournament}/edit', 'Site\PersonalController@edit')
    ->middleware('can:create,App\Models\PersonalTournament')
    ->name('personal.tournament.edit');
Route::get('/personal/{personalTournament}/player/{player}', 'Site\PersonalController@player')
    ->middleware('can:create,App\Models\PersonalTournament')
    ->name('personal.tournament.player');
Route::get('/personal/{personalTournament}/map', 'Site\PersonalController@map')
    ->name('personal.tournament.map');
Route::get('/personal/{personalTournament}/copypaste', 'Site\PersonalController@players')
    ->middleware('can:create,App\Models\PersonalTournament')
    ->name('personal.tournament.copypaste');

/*
|--------------------------------------------------------------------------
| Regular
|--------------------------------------------------------------------------
*/
Route::get('/personal/{personalTournament}/regular', 'Site\PersonalRegularController@index')
    ->name('personal.tournament.regular');
Route::get('/personal/{personalTournament}/regular/games', 'Site\PersonalRegularController@games')
    ->name('personal.tournament.regular.games');
Route::get('/personal/{personalTournament}/regular/games/{personalGameRegular}', 'Site\PersonalRegularController@game')
    ->name('personal.tournament.regular.game');
Route::get('/personal/{personalTournament}/regular/games/{personalGameRegular}/edit', 'Site\PersonalRegularController@gameEdit')
    ->middleware('can:update,personalGameRegular')
    ->name('personal.tournament.regular.game.edit');
Route::get('/personal/{personalTournament}/regular/schedule', 'Site\PersonalRegularController@schedule')
    ->name('personal.tournament.regular.schedule');

/*
|--------------------------------------------------------------------------
| Playoff
|--------------------------------------------------------------------------
*/
Route::get('/personal/{personalTournament}/playoff', 'Site\PersonalPlayoffController@index')
    ->name('personal.tournament.playoff');
Route::get('/personal/{personalTournament}/playoff/games', 'Site\PersonalPlayoffController@index')
    ->name('personal.tournament.playoff.games');
Route::get(
    '/personal/{personalTournament}/playoff/games/{personalTournamentPlayoff}/add',
    'Site\PersonalPlayoffController@gameAdd'
)
    ->middleware('can:update,personalTournamentPlayoff')
    ->name('personal.tournament.playoff.game.add');
Route::get(
    '/personal/{personalTournament}/playoff/games/{personalTournamentPlayoff}/{personalGamePlayoff}',
    'Site\PersonalPlayoffController@game'
)
    ->name('personal.tournament.playoff.game');
Route::get(
    '/personal/{personalTournament}/playoff/games/{personalTournamentPlayoff}/{personalGamePlayoff}/edit',
    'Site\PersonalPlayoffController@gameEdit'
)
    ->middleware('can:update,personalTournamentPlayoff')
    ->name('personal.tournament.playoff.game.edit');
