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

use Illuminate\Support\Facades\Route;

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
| Group
|--------------------------------------------------------------------------
*/
Route::get('/group', 'Site\GroupController@index')
    ->name('group');
Route::get('/group/new', 'Site\GroupController@new')
    ->name('group.new');
Route::get('/group/{tournamentId}', 'Site\GroupController@teams')
    ->where(['tournamentId' => '[0-9]+'])
    ->name('group.tournament');
Route::get('/group/{tournamentId}/edit', 'Site\GroupController@edit')
    ->where(['tournamentId' => '[0-9]+'])
    ->name('group.tournament.edit');
Route::get('/group/{tournamentId}/team/{teamId}', 'Site\GroupController@team')
    ->where(['tournamentId' => '[0-9]+', 'teamId' => '[0-9]+'])
    ->name('group.tournament.team');
/*
 * Regular
 */
Route::get('/group/{tournamentId}/regular', 'Site\GroupRegularController@index')
    ->where(['tournamentId' => '[0-9]+'])
    ->name('group.tournament.regular');
Route::get('/group/{tournamentId}/regular/games', 'Site\GroupRegularController@games')
    ->where(['tournamentId' => '[0-9]+'])
    ->name('group.tournament.regular.games');
Route::get('/group/{tournamentId}/regular/games/{gameId}', 'Site\GroupRegularController@game')
    ->where(['tournamentId' => '[0-9]+', 'gameId' => '[0-9]+'])
    ->name('group.tournament.regular.game');
Route::get('/group/{tournamentId}/regular/games/{gameId}/edit', 'Site\GroupRegularController@gameEdit')
    ->where(['tournamentId' => '[0-9]+', 'gameId' => '[0-9]+'])
    ->name('group.tournament.regular.game.edit');
Route::get('/group/{tournamentId}/regular/schedule', 'Site\GroupRegularController@schedule')
    ->where(['tournamentId' => '[0-9]+', 'gameId' => '[0-9]+'])
    ->name('group.tournament.regular.schedule');
/*
 * Playoff
 */
Route::get('/group/{tournamentId}/playoff', 'Site\GroupPlayoffController@index')
    ->where(['tournamentId' => '[0-9]+'])
    ->name('group.tournament.playoff');
Route::get('/group/{tournamentId}/playoff/stats', 'Site\GroupPlayoffController@stats')
    ->where(['tournamentId' => '[0-9]+'])
    ->name('group.tournament.playoff.stats');
Route::get('/group/{tournamentId}/playoff/games', 'Site\GroupPlayoffController@games')
    ->where(['tournamentId' => '[0-9]+'])
    ->name('group.tournament.playoff.games');
Route::get('/group/{tournamentId}/playoff/games/{pairId}/add', 'Site\GroupPlayoffController@gameAdd')
    ->where(['tournamentId' => '[0-9]+', 'pairId' => '[0-9]+'])
    ->name('group.tournament.playoff.game.add');
Route::get('/group/{tournamentId}/playoff/games/{pairId}/{gameId}', 'Site\GroupPlayoffController@game')
    ->where(['tournamentId' => '[0-9]+', 'pairId' => '[0-9]+', 'gameId' => '[0-9]+'])
    ->name('group.tournament.playoff.game');
Route::get('/group/{tournamentId}/playoff/games/{pairId}/{gameId}/edit', 'Site\GroupPlayoffController@gameEdit')
    ->where(['tournamentId' => '[0-9]+', 'pairId' => '[0-9]+', 'gameId' => '[0-9]+'])
    ->name('group.tournament.playoff.game.edit');

Route::get('/group/{tournamentId}/copypaste', 'Site\GroupController@teams')
    ->where(['tournamentId' => '[0-9]+'])
    ->name('group.tournament.copypaste');

/*
|--------------------------------------------------------------------------
| Personal
|--------------------------------------------------------------------------
*/
Route::get('/personal', 'Site\PersonalController@index');

/*
|--------------------------------------------------------------------------
| Team
|--------------------------------------------------------------------------
*/
Route::get('/team', 'Site\TeamController@index')
    ->name('teams');
Route::get('/team/{teamId}', 'Site\TeamController@team')
    ->where(['teamId' => '[0-9]+'])
    ->name('team');

/*
|--------------------------------------------------------------------------
| Player
|--------------------------------------------------------------------------
*/
Route::get('/player', 'Site\PlayerController@index')
    ->name('players');
Route::get('/player/{playerId}', 'Site\PlayerController@player')
    ->where(['playerId' => '[0-9]+'])
    ->name('player');

/*
|--------------------------------------------------------------------------
| AJAX
|--------------------------------------------------------------------------
*/
Route::put('/ajax/group', 'Ajax\GroupController@create');
Route::post('/ajax/group/{tournamentId}', 'Ajax\GroupController@edit')
    ->where(['tournamentId' => '[0-9]+']);
Route::delete('/ajax/group/{tournamentId}', 'Ajax\GroupController@delete')
    ->where(['tournamentId' => '[0-9]+']);

Route::put('/ajax/group/{tournamentId}/team', 'Ajax\GroupController@addTeam')
    ->where(['tournamentId' => '[0-9]+']);
Route::post('/ajax/group/{tournamentId}/team/{teamId}', 'Ajax\GroupController@editTeam')
    ->where(['tournamentId' => '[0-9]+', 'teamId' => '[0-9]+']);
Route::delete('/ajax/group/{tournamentId}/team/{teamId}', 'Ajax\GroupController@deleteTeam')
    ->where(['tournamentId' => '[0-9]+', 'teamId' => '[0-9]+']);

Route::post('/ajax/group/{tournamentId}/regular/{gameId}', 'Ajax\GroupController@editRegularGame')
    ->where(['tournamentId' => '[0-9]+', 'gameId' => '[0-9]+']);
Route::post('/ajax/group/{tournamentId}/regular/{gameId}/reset', 'Ajax\GroupController@resetRegularGame')
    ->where(['tournamentId' => '[0-9]+', 'gameId' => '[0-9]+']);
Route::put('/ajax/group/{tournamentId}/regular/{gameId}/protocol', 'Ajax\GroupController@createRegularProtocol')
    ->where(['tournamentId' => '[0-9]+', 'gameId' => '[0-9]+']);
Route::delete(
    '/ajax/group/{tournamentId}/regular/{gameId}/protocol/{protocolId}',
    'Ajax\GroupController@deleteRegularProtocol'
)->where(['tournamentId' => '[0-9]+', 'gameId' => '[0-9]+', 'protocolId' => '[0-9]+']);
Route::post(
    '/ajax/group/{tournamentId}/regular/{gameId}/protocol/{protocolId}',
    'Ajax\GroupController@updateRegularProtocol'
)->where(['tournamentId' => '[0-9]+', 'gameId' => '[0-9]+', 'protocolId' => '[0-9]+']);

Route::put('/ajax/group/{tournamentId}/playoff', 'Ajax\GroupController@createPair')
    ->where(['tournamentId' => '[0-9]+']);
Route::post('/ajax/group/{tournamentId}/playoff/{pairId}', 'Ajax\GroupController@updatePair')
    ->where(['tournamentId' => '[0-9]+', 'pairId' => '[0-9]+']);
Route::put('/ajax/group/{tournamentId}/playoff/{pairId}', 'Ajax\GroupController@createPlayoffGame')
    ->where(['tournamentId' => '[0-9]+', 'pairId' => '[0-9]+']);
Route::post('/ajax/group/{tournamentId}/playoff/{pairId}/{gameId}', 'Ajax\GroupController@editPlayoffGame')
    ->where(['tournamentId' => '[0-9]+', 'pairId' => '[0-9]+', 'gameId' => '[0-9]+']);
Route::post('/ajax/group/{tournamentId}/playoff/{pairId}/{gameId}/reset', 'Ajax\GroupController@resetPlayoffGame')
    ->where(['tournamentId' => '[0-9]+', 'gameId' => '[0-9]+']);
Route::put('/ajax/group/{tournamentId}/playoff/{pairId}/{gameId}/protocol', 'Ajax\GroupController@createPlayoffProtocol')
    ->where(['tournamentId' => '[0-9]+', 'gameId' => '[0-9]+']);
Route::delete(
    '/ajax/group/{tournamentId}/playoff/{pairId}/{gameId}/protocol/{protocolId}',
    'Ajax\GroupController@deletePlayoffProtocol'
)->where(['tournamentId' => '[0-9]+', 'gameId' => '[0-9]+', 'protocolId' => '[0-9]+']);
Route::post(
    '/ajax/group/{tournamentId}/playoff/{pairId}/{gameId}/protocol/{protocolId}',
    'Ajax\GroupController@updatePlayoffProtocol'
)->where(['tournamentId' => '[0-9]+', 'gameId' => '[0-9]+', 'protocolId' => '[0-9]+']);

Route::get('/ajax/ea/lastGames', 'Ajax\EaController@getLastGames');
