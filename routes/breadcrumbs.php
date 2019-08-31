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
//Group > Tournament > VK
Breadcrumbs::for('group.tournament.copypaste', function ($trail, $tournament) {
    $trail->parent('group.tournament', $tournament);
    $trail->push(
        'Данные для ВК',
        route('group.tournament.copypaste', ['tournamentId' => $tournament->id])
    );
});
//Group > Tournament > Regular
Breadcrumbs::for('group.tournament.regular', function ($trail, $tournament) {
    $trail->parent('group.tournament', $tournament);
    $trail->push(
        'Чемпионат',
        route('group.tournament.regular', ['tournamentId' => $tournament->id])
    );
});
//Group > Tournament > Regular > Games
Breadcrumbs::for('group.tournament.regular.games', function ($trail, $tournament) {
    $trail->parent('group.tournament.regular', $tournament);
    $trail->push(
        'Расписание',
        route('group.tournament.regular.games', ['tournamentId' => $tournament->id])
    );
});
//Group > Tournament > Regular > Games > Game
Breadcrumbs::for('group.tournament.regular.game', function ($trail, $game) {
    $trail->parent('group.tournament.regular.games', $game->tournament);
    $trail->push(
        'Тур ' . $game->round . ': ' . $game->homeTeam->team->name . ' vs. ' . $game->awayTeam->team->name,
        route(
            'group.tournament.regular.game',
            ['tournamentId' => $game->tournament->id, 'gameId' => $game->id]
        )
    );
});
//Group > Tournament > Regular > VK
Breadcrumbs::for('group.tournament.regular.schedule', function ($trail, $tournament) {
    $trail->parent('group.tournament.regular', $tournament);
    $trail->push(
        'Расписание ВК',
        route('group.tournament.regular.schedule', ['tournamentId' => $tournament->id])
    );
});
//Group > Tournament > Playoff
Breadcrumbs::for('group.tournament.playoff', function ($trail, $tournament) {
    $trail->parent('group.tournament', $tournament);
    $trail->push(
        'Плей-офф',
        route('group.tournament.playoff', ['tournamentId' => $tournament->id])
    );
});
