<?php

/*
|--------------------------------------------------------------------------
| AJAX group tournaments routes
|--------------------------------------------------------------------------
|
| Here is where you can register AJAX web routes for group tournaments.
| These routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group.
|
*/

/*
|--------------------------------------------------------------------------
| Common
|--------------------------------------------------------------------------
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

/*
|--------------------------------------------------------------------------
| Regular
|--------------------------------------------------------------------------
*/
Route::post(
    '/ajax/group/{groupTournament}/regular/{groupGameRegular}',
    'Ajax\GroupController@editRegularGame'
)
    ->middleware('can:update,groupGameRegular');
Route::post(
    '/ajax/group/{groupTournament}/regular/{groupGameRegular}/confirm',
    'Ajax\GroupController@confirmRegularResult'
)
    ->middleware('can:update,groupGameRegular');
Route::post(
    '/ajax/group/{groupTournament}/regular/{groupGameRegular}/reset',
    'Ajax\GroupController@resetRegularGame'
)
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

/*
|--------------------------------------------------------------------------
| Playoff
|--------------------------------------------------------------------------
*/
Route::put('/ajax/group/{groupTournament}/playoff', 'Ajax\GroupController@createPair')
    ->middleware('can:create,App\Models\GroupTournament');
Route::post(
    '/ajax/group/{groupTournament}/playoff/{groupTournamentPlayoff}',
    'Ajax\GroupController@updatePair'
)
    ->middleware('can:create,App\Models\GroupTournament');
Route::put(
    '/ajax/group/{groupTournament}/playoff/{groupTournamentPlayoff}',
    'Ajax\GroupController@createPlayoffGame'
)
    ->middleware('can:update,groupTournamentPlayoff');
Route::post(
    '/ajax/group/{groupTournament}/playoff/{groupTournamentPlayoff}/{groupGamePlayoff}',
    'Ajax\GroupController@editPlayoffGame'
)
    ->middleware('can:update,groupTournamentPlayoff');
Route::post(
    '/ajax/group/{groupTournament}/playoff/{groupTournamentPlayoff}/{groupGamePlayoff}/confirm',
    'Ajax\GroupController@confirmPlayoffResult'
)
    ->middleware('can:update,groupGameRegular');
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

/*
|--------------------------------------------------------------------------
| Service
|--------------------------------------------------------------------------
*/
/*
 * EA
 */
Route::get('/ajax/ea/lastGames', 'Ajax\EaController@getLastGames');
/*
 * VK
 */
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