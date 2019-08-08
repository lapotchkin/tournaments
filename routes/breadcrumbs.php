<?php

// Group
Breadcrumbs::for('group', function ($trail) {
    $trail->push('Командные турниры', action('Site\GroupController@index'));
});
//Group > New
Breadcrumbs::for('group.new', function ($trail) {
    $trail->parent('group');
    $trail->push('Новый турнир', action('Site\GroupController@new'));
});
//Group > Tournament
Breadcrumbs::for('group.tournament', function ($trail, $tournament) {
    $trail->parent('group');
    $trail->push(
        $tournament->title,
        action('Site\GroupController@teams', ['tournamentId' => $tournament->id])
    );
});
//Group > Tournament > Editor
Breadcrumbs::for('group.tournament.edit', function ($trail, $tournament) {
    $trail->parent('group.tournament', $tournament);
    $trail->push(
        'Редактировать турнир',
        action('Site\GroupController@edit', ['tournamentId' => $tournament->id])
    );
});
//Group > Tournament > Team
Breadcrumbs::for('group.tournament.team', function ($trail, $tournamentTeam, $title) {
    $trail->parent('group.tournament', $tournamentTeam->tournament);
    $trail->push(
        $title,
        action(
            'Site\GroupController@team',
            [
                'tournamentId' => $tournamentTeam->tournament->id,
                'teamId'       => $tournamentTeam->team_id,
            ]
        )
    );
});
