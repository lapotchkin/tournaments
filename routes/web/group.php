<?php

////

/*
|--------------------------------------------------------------------------
| Common
|--------------------------------------------------------------------------
*/
//The list of the group tournaments
Route::get('/group', 'Site\GroupController@index')
    ->name('group');
//The form for creating a new tournament
Route::get('/group/new', 'Site\GroupController@create')
    ->middleware('can:create,App\Models\GroupTournament')
    ->name('group.new');
//The list of the tournament teams
Route::get('/group/{groupTournament}', 'Site\GroupController@teams')
    ->name('group.tournament');
//The form for editing the tournament
Route::get('/group/{groupTournament}/edit', 'Site\GroupController@edit')
    ->middleware('can:create,App\Models\GroupTournament')
    ->name('group.tournament.edit');
//The form for changing the team settings for the tournament
Route::get('/group/{groupTournament}/team/{team}', 'Site\GroupController@team')
    ->middleware('can:create,App\Models\GroupTournament')
    ->name('group.tournament.team');
//The tournament data for social networks
Route::get('/group/{groupTournament}/copypaste', 'Site\GroupController@teams')
    ->middleware('can:create,App\Models\GroupTournament')
    ->name('group.tournament.copypaste');

/*
|--------------------------------------------------------------------------
| Regular
|--------------------------------------------------------------------------
*/
Route::get('/group/{groupTournament}/regular', 'Site\GroupRegularController@index')
    ->name('group.tournament.regular');
Route::get('/group/{groupTournament}/regular/games', 'Site\GroupRegularController@games')
    ->name('group.tournament.regular.games');
Route::get('/group/{groupTournament}/regular/games/{groupGameRegular}', 'Site\GroupRegularController@game')
    ->name('group.tournament.regular.game');
Route::get('/group/{groupTournament}/regular/games/{groupGameRegular}/edit', 'Site\GroupRegularController@gameEdit')
    ->middleware('can:update,groupGameRegular')
    ->name('group.tournament.regular.game.edit');
Route::get('/group/{groupTournament}/regular/schedule', 'Site\GroupRegularController@schedule')
    ->name('group.tournament.regular.schedule');

/*
|--------------------------------------------------------------------------
| Playoff
|--------------------------------------------------------------------------
*/
Route::get('/group/{groupTournament}/playoff', 'Site\GroupPlayoffController@index')
    ->name('group.tournament.playoff');
Route::get('/group/{groupTournament}/playoff/stats', 'Site\GroupPlayoffController@stats')
    ->name('group.tournament.playoff.stats');
Route::get('/group/{groupTournament}/playoff/games', 'Site\GroupPlayoffController@games')
    ->name('group.tournament.playoff.games');
Route::get(
    '/group/{groupTournament}/playoff/games/{groupTournamentPlayoff}/add',
    'Site\GroupPlayoffController@gameAdd'
)
    ->middleware('can:update,groupTournamentPlayoff')
    ->name('group.tournament.playoff.game.add');
Route::get(
    '/group/{groupTournament}/playoff/games/{groupTournamentPlayoff}/{groupGamePlayoff}',
    'Site\GroupPlayoffController@game'
)->name('group.tournament.playoff.game');
Route::get(
    '/group/{groupTournament}/playoff/games/{groupTournamentPlayoff}/{groupGamePlayoff}/edit',
    'Site\GroupPlayoffController@gameEdit'
)
    ->middleware('can:update,groupGamePlayoff')
    ->name('group.tournament.playoff.game.edit');
