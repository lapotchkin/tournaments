<?php

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