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
    ->middleware('can:create,App\Models\GroupTournament')
    ->name('group.new');
Route::get('/group/{groupTournament}', 'Site\GroupController@teams')
    ->name('group.tournament');
Route::get('/group/{groupTournament}/edit', 'Site\GroupController@edit')
    ->middleware('can:create,App\Models\GroupTournament')
    ->name('group.tournament.edit');
Route::get('/group/{groupTournament}/team/{team}', 'Site\GroupController@team')
    ->middleware('can:create,App\Models\GroupTournament')
    ->name('group.tournament.team');
/*
 * Regular
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
 * Playoff
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
    ->middleware('can:update,groupTournamentPlayoff')
    ->name('group.tournament.playoff.game.edit');

Route::get('/group/{groupTournament}/copypaste', 'Site\GroupController@teams')
    ->middleware('can:create,App\Models\GroupTournament')
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
| Tracker
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
Route::put('/ajax/group', 'Ajax\GroupController@create')
    ->middleware('can:create,App\Models\GroupTournament');
Route::post('/ajax/group/{groupTournament}', 'Ajax\GroupController@edit')
    ->middleware('can:create,App\Models\GroupTournament');
Route::delete('/ajax/group/{groupTournament}', 'Ajax\GroupController@delete')
    ->middleware('can:create,App\Models\GroupTournament');
Route::post('/ajax/group/{groupTournament}/winner', 'Ajax\GroupController@setWinner')
    ->middleware('can:create,App\Models\GroupTournament');

Route::put('/ajax/group/{groupTournament}/team', 'Ajax\GroupController@addTeam')
    ->middleware('can:create,App\Models\GroupTournament');
Route::post('/ajax/group/{groupTournament}/team/{team}', 'Ajax\GroupController@editTeam')
    ->middleware('can:create,App\Models\GroupTournament');
Route::delete('/ajax/group/{groupTournament}/team/{team}', 'Ajax\GroupController@deleteTeam')
    ->middleware('can:create,App\Models\GroupTournament');
//Regular
Route::post('/ajax/group/{groupTournament}/regular/{groupGameRegular}', 'Ajax\GroupController@editRegularGame')
    ->middleware('can:update,groupGameRegular');
Route::post('/ajax/group/{groupTournament}/regular/{groupGameRegular}/reset', 'Ajax\GroupController@resetRegularGame')
    ->middleware('can:update,groupGameRegular');
Route::put(
    '/ajax/group/{groupTournament}/regular/{groupGameRegular}/protocol',
    'Ajax\GroupController@createRegularProtocol'
)
    ->middleware('can:update,groupGameRegular');
Route::delete(
    '/ajax/group/{groupTournament}/regular/{groupGameRegular}/protocol/{groupGameRegular_player}',
    'Ajax\GroupController@deleteRegularProtocol'
)
    ->middleware('can:update,groupGameRegular');
Route::post(
    '/ajax/group/{groupTournament}/regular/{groupGameRegular}/protocol/{groupGameRegular_player}',
    'Ajax\GroupController@updateRegularProtocol'
)
    ->middleware('can:update,groupGameRegular');
//Playoff
Route::put('/ajax/group/{groupTournament}/playoff', 'Ajax\GroupController@createPair')
    ->middleware('can:create,App\Models\GroupTournament');
Route::post('/ajax/group/{groupTournament}/playoff/{groupTournamentPlayoff}', 'Ajax\GroupController@updatePair')
    ->middleware('can:create,App\Models\GroupTournament');
Route::put('/ajax/group/{groupTournament}/playoff/{groupTournamentPlayoff}', 'Ajax\GroupController@createPlayoffGame')
    ->middleware('can:update,groupTournamentPlayoff');
Route::post(
    '/ajax/group/{groupTournament}/playoff/{groupTournamentPlayoff}/{groupGamePlayoff}',
    'Ajax\GroupController@editPlayoffGame'
)
    ->middleware('can:update,groupTournamentPlayoff');
Route::post(
    '/ajax/group/{groupTournament}/playoff/{groupTournamentPlayoff}/{groupGamePlayoff}/reset',
    'Ajax\GroupController@resetPlayoffGame'
)
    ->middleware('can:update,groupTournamentPlayoff');
Route::put(
    '/ajax/group/{groupTournament}/playoff/{groupTournamentPlayoff}/{groupGamePlayoff}/protocol',
    'Ajax\GroupController@createPlayoffProtocol'
)
    ->middleware('can:update,groupTournamentPlayoff');
Route::delete(
    '/ajax/group/{groupTournament}/playoff/{groupTournamentPlayoff}/{groupGamePlayoff}/protocol/{groupGamePlayoff_player}',
    'Ajax\GroupController@deletePlayoffProtocol'
)
    ->middleware('can:update,groupTournamentPlayoff');
Route::post(
    '/ajax/group/{groupTournament}/playoff/{groupTournamentPlayoff}/{groupGamePlayoff}/protocol/{groupGamePlayoff_player}',
    'Ajax\GroupController@updatePlayoffProtocol'
)
    ->middleware('can:update,groupTournamentPlayoff');
//EA
Route::get('/ajax/ea/lastGames', 'Ajax\EaController@getLastGames');
//VK
Route::post(
    '/ajax/group/{groupTournament}/regular/{groupGameRegular}/share',
    'Ajax\GroupController@shareRegularResult'
)
    ->middleware('can:create,App\Models\GroupTournament');
Route::post(
    '/ajax/group/{groupTournament}/playoff/{groupTournamentPlayoff}/{groupGamePlayoff}/share',
    'Ajax\GroupController@sharePlayoffResult'
)
    ->middleware('can:create,App\Models\GroupTournament');

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
