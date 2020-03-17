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
 * Regular
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
 * Playoff
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

/*
|--------------------------------------------------------------------------
| Player
|--------------------------------------------------------------------------
*/
Route::get('/player', 'Site\PlayerController@index')
    ->name('players');
Route::get('/player/add', 'Site\PlayerController@add')
    ->middleware('can:create,App\Models\Player')
    ->name('player.add');
Route::get('/player/{player}', 'Site\PlayerController@player')
    ->name('player');
Route::get('/player/{player}/edit', 'Site\PlayerController@edit')
    ->middleware('can:update,player')
    ->name('player.edit');

/*
|--------------------------------------------------------------------------
| Team
|--------------------------------------------------------------------------
*/
Route::get('/team', 'Site\TeamController@index')
    ->name('teams');
Route::get('/team/add', 'Site\TeamController@add')
    ->middleware('can:create,App\Models\Team')
    ->name('team.add');
Route::get('/team/{team}', 'Site\TeamController@team')
    ->name('team');
Route::get('/team/{team}/edit', 'Site\TeamController@edit')
    ->middleware('can:create,App\Models\Team')
    ->name('team.edit');

/*
|--------------------------------------------------------------------------
| Team
|--------------------------------------------------------------------------
*/
Route::get('/tracker', 'Site\TrackerController@index')
    ->name('tracker');

/*
|--------------------------------------------------------------------------
| AJAX
|--------------------------------------------------------------------------
*/
/*
 * Group
 */
Route::put('/ajax/group', 'Ajax\GroupController@create');
Route::post('/ajax/group/{tournamentId}', 'Ajax\GroupController@edit')
    ->where(['tournamentId' => '[0-9]+']);
Route::delete('/ajax/group/{tournamentId}', 'Ajax\GroupController@delete')
    ->where(['tournamentId' => '[0-9]+']);
Route::post('/ajax/group/{tournamentId}/winner', 'Ajax\GroupController@setWinner')
    ->where(['tournamentId' => '[0-9]+']);

Route::put('/ajax/group/{tournamentId}/team', 'Ajax\GroupController@addTeam')
    ->where(['tournamentId' => '[0-9]+']);
Route::post('/ajax/group/{tournamentId}/team/{teamId}', 'Ajax\GroupController@editTeam')
    ->where(['tournamentId' => '[0-9]+', 'teamId' => '[0-9]+']);
Route::delete('/ajax/group/{tournamentId}/team/{teamId}', 'Ajax\GroupController@deleteTeam')
    ->where(['tournamentId' => '[0-9]+', 'teamId' => '[0-9]+']);
//Regular
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
//Playoff
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
Route::put(
    '/ajax/group/{tournamentId}/playoff/{pairId}/{gameId}/protocol',
    'Ajax\GroupController@createPlayoffProtocol'
)
    ->where(['tournamentId' => '[0-9]+', 'gameId' => '[0-9]+']);
Route::delete(
    '/ajax/group/{tournamentId}/playoff/{pairId}/{gameId}/protocol/{protocolId}',
    'Ajax\GroupController@deletePlayoffProtocol'
)->where(['tournamentId' => '[0-9]+', 'gameId' => '[0-9]+', 'protocolId' => '[0-9]+']);
Route::post(
    '/ajax/group/{tournamentId}/playoff/{pairId}/{gameId}/protocol/{protocolId}',
    'Ajax\GroupController@updatePlayoffProtocol'
)->where(['tournamentId' => '[0-9]+', 'gameId' => '[0-9]+', 'protocolId' => '[0-9]+']);
//EA
Route::get('/ajax/ea/lastGames', 'Ajax\EaController@getLastGames');
//VK
Route::post('/ajax/group/{tournamentId}/regular/{gameId}/share', 'Ajax\GroupController@shareRegularResult')
    ->where(['tournamentId' => '[0-9]+', 'gameId' => '[0-9]+']);
Route::post('/ajax/group/{tournamentId}/playoff/{pairId}/{gameId}/share', 'Ajax\GroupController@sharePlayoffResult')
    ->where(['tournamentId' => '[0-9]+', 'pairId' => '[0-9]+', 'gameId' => '[0-9]+']);

/*
 * Personal
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
//Regular
Route::post('/ajax/personal/{tournamentId}/regular/{gameId}', 'Ajax\PersonalController@editRegularGame')
    ->where(['tournamentId' => '[0-9]+', 'gameId' => '[0-9]+']);
//Playoff
Route::put('/ajax/personal/{tournamentId}/playoff', 'Ajax\PersonalController@createPair')
    ->where(['tournamentId' => '[0-9]+']);
Route::post('/ajax/personal/{tournamentId}/playoff/{pairId}', 'Ajax\PersonalController@updatePair')
    ->where(['tournamentId' => '[0-9]+', 'pairId' => '[0-9]+']);
Route::put('/ajax/personal/{tournamentId}/playoff/{pairId}', 'Ajax\PersonalController@createPlayoffGame')
    ->where(['tournamentId' => '[0-9]+', 'pairId' => '[0-9]+']);
Route::post('/ajax/personal/{tournamentId}/playoff/{pairId}/{gameId}', 'Ajax\PersonalController@editPlayoffGame')
    ->where(['tournamentId' => '[0-9]+', 'pairId' => '[0-9]+', 'gameId' => '[0-9]+']);
//VK
Route::post('/ajax/personal/{tournamentId}/regular/{gameId}/share', 'Ajax\PersonalController@shareRegularResult')
    ->where(['tournamentId' => '[0-9]+', 'gameId' => '[0-9]+']);
Route::post(
    '/ajax/personal/{tournamentId}/playoff/{pairId}/{gameId}/share',
    'Ajax\PersonalController@sharePlayoffResult'
)
    ->where(['tournamentId' => '[0-9]+', 'pairId' => '[0-9]+', 'gameId' => '[0-9]+']);

/*
 * Player
 */
Route::put('/ajax/player', 'Ajax\PlayerController@create');
Route::post('/ajax/player/{player}', 'Ajax\PlayerController@edit')
    ->middleware('can:update,player');
Route::delete('/ajax/player/{player}', 'Ajax\PlayerController@delete')
    ->middleware('can:create,App\Models\Player');

/*
 * Team
 */
Route::put('/ajax/team', 'Ajax\TeamController@create')
    ->middleware('can:create,App\Models\Team');
Route::post('/ajax/team/{team}', 'Ajax\TeamController@edit')
    ->middleware('can:create,App\Models\Team');
Route::delete('/ajax/team/{team}', 'Ajax\TeamController@delete')
    ->middleware('can:create,App\Models\Team');;
Route::put('/ajax/team/{team}', 'Ajax\TeamController@addPlayer')
    ->middleware('can:update,team');
Route::post('/ajax/team/{team}/app', 'Ajax\TeamController@setTeamId')
    ->middleware('can:create,App\Models\Team');
Route::delete('/ajax/team/{team}/app/{app}', 'Ajax\TeamController@deleteTeamId')
    ->middleware('can:create,App\Models\Team');
Route::post('/ajax/team/{team}/{player}', 'Ajax\TeamController@updatePlayer')
    ->middleware('can:update,team');
Route::delete('/ajax/team/{team}/{player}', 'Ajax\TeamController@deletePlayer')
    ->middleware('can:update,team');
