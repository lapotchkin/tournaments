<?php

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