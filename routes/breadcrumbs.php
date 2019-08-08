<?php

// Group
Breadcrumbs::for('group', function ($trail) {
    $trail->push('Командные турниры', route('group'));
});
//Group > New
Breadcrumbs::for('group.new', function ($trail) {
    $trail->parent('group');
    $trail->push('Новый турнир', route('group.new'));
});
//Group > Tournament
Breadcrumbs::for('group.tournament', function ($trail, $tournament) {
    $trail->parent('group');
    $trail->push(
        $tournament->title,
        route('group.tournament', ['tournamentId' => $tournament->id])
    );
});
//Group > Tournament > Editor
Breadcrumbs::for('group.tournament.edit', function ($trail, $tournament) {
    $trail->parent('group.tournament', $tournament);
    $trail->push(
        'Редактировать турнир',
        route('group.tournament.edit', ['tournamentId' => $tournament->id])
    );
});
//Group > Tournament > Team
Breadcrumbs::for('group.tournament.team', function ($trail, $tournamentTeam, $title) {
    $trail->parent('group.tournament', $tournamentTeam->tournament);
    $trail->push(
        $title,
        route(
            'group.tournament.team',
            ['tournamentId' => $tournamentTeam->tournament->id, 'teamId' => $tournamentTeam->team_id]
        )
    );
});
//Group > Tournament > Editor
Breadcrumbs::for('group.tournament.copypaste', function ($trail, $tournament) {
    $trail->parent('group.tournament', $tournament);
    $trail->push(
        'Данные для ВК',
        route('group.tournament.copypaste', ['tournamentId' => $tournament->id])
    );
});