<?php

// Group
use App\Models\GroupGamePlayoff;
use App\Models\GroupTournamentPlayoff;
use App\Models\PersonalGamePlayoff;
use App\Models\PersonalGameRegular;
use App\Models\PersonalTournament;
use App\Models\PersonalTournamentPlayer;
use App\Models\PersonalTournamentPlayoff;
use App\Models\Player;

//Group
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
//Group > Tournament > Playoff > Stats
Breadcrumbs::for('group.tournament.playoff.stats', function ($trail, $tournament) {
    $trail->parent('group.tournament', $tournament);
    $trail->push(
        'Статистика',
        route('group.tournament.playoff.stats', ['tournamentId' => $tournament->id])
    );
});
//Group > Tournament > Playoff > Game
Breadcrumbs::for('group.tournament.playoff.game', function ($trail, GroupGamePlayoff $game) {
    $trail->parent('group.tournament.playoff', $game->tournament);
    $roundText = TextUtils::playoffRound($game->tournament, $game->playoffPair->round);
    $pairText = strstr($roundText, 'финала') ? ' (пара ' . $game->playoffPair->pair . ')' : '';
    $trail->push(
        $roundText . $pairText . ': ' . $game->homeTeam->team->name . ' vs. ' . $game->awayTeam->team->name,
        route(
            'group.tournament.playoff.game',
            ['tournamentId' => $game->tournament->id, 'pairId' => $game->playoff_pair_id, 'gameId' => $game->id]
        )
    );
});
//Group > Tournament > Playoff > Games
Breadcrumbs::for('group.tournament.playoff.games', function ($trail, $tournament) {
    $trail->parent('group.tournament.playoff', $tournament);
    $trail->push(
        'Расписание',
        route(
            'group.tournament.playoff.games',
            ['tournamentId' => $tournament->id]
        )
    );
});
//Group > Tournament > Playoff > Games > New Game
Breadcrumbs::for('group.tournament.playoff.game.add', function ($trail, GroupTournamentPlayoff $pair) {
    $trail->parent('group.tournament.playoff.games', $pair->tournament);
    $roundText = TextUtils::playoffRound($pair->tournament, $pair->round);
    $pairText = strstr($roundText, 'финала') ? ' (пара ' . $pair->pair . ')' : '';
    $trail->push(
        $roundText . $pairText,
        route(
            'group.tournament.playoff.game.add',
            ['tournamentId' => $pair->tournament->id, 'pairId' => $pair->id]
        )
    );
});


//Personal
Breadcrumbs::for('personal', function ($trail) {
    $trail->push('Турниры 1 на 1 ', route('personal'));
});
//Personal > New
Breadcrumbs::for('personal.new', function ($trail) {
    $trail->parent('personal');
    $trail->push('Новый турнир', route('personal.new'));
});
//Personal > Tournament
Breadcrumbs::for('personal.tournament', function ($trail, PersonalTournament $tournament) {
    $trail->parent('personal');
    $trail->push(
        $tournament->title,
        route('personal.tournament', ['tournamentId' => $tournament->id])
    );
});
//Personal > Tournament > Map
Breadcrumbs::for('personal.tournament.map', function ($trail, PersonalTournament $tournament) {
    $trail->parent('personal.tournament', $tournament);
    $trail->push(
        'Карта игроков',
        route('personal.tournament.map', ['tournamentId' => $tournament->id])
    );
});
//Personal > Tournament > Editor
Breadcrumbs::for('personal.tournament.edit', function ($trail, PersonalTournament $tournament) {
    $trail->parent('personal.tournament', $tournament);
    $trail->push(
        'Редактировать турнир',
        route('personal.tournament.edit', ['tournamentId' => $tournament->id])
    );
});
//Personal > Tournament > Player
Breadcrumbs::for('personal.tournament.player', function ($trail, PersonalTournamentPlayer $tournamentPlayer, $title) {
    $trail->parent('personal.tournament', $tournamentPlayer->tournament);
    $trail->push(
        $title,
        route(
            'personal.tournament.player',
            ['tournamentId' => $tournamentPlayer->tournament->id, 'playerId' => $tournamentPlayer->player_id]
        )
    );
});
//Personal > Tournament > VK
Breadcrumbs::for('personal.tournament.copypaste', function ($trail, PersonalTournament $tournament) {
    $trail->parent('personal.tournament', $tournament);
    $trail->push(
        'Данные для ВК',
        route('personal.tournament.copypaste', ['tournamentId' => $tournament->id])
    );
});
//Personal > Tournament > Regular
Breadcrumbs::for('personal.tournament.regular', function ($trail, PersonalTournament $tournament) {
    $trail->parent('personal.tournament', $tournament);
    $trail->push(
        'Чемпионат',
        route('personal.tournament.regular', ['tournamentId' => $tournament->id])
    );
});
//Personal > Tournament > Regular > Games
Breadcrumbs::for('personal.tournament.regular.games', function ($trail, PersonalTournament $tournament) {
    $trail->parent('personal.tournament.regular', $tournament);
    $trail->push(
        'Расписание',
        route('personal.tournament.regular.games', ['tournamentId' => $tournament->id])
    );
});
//Personal > Tournament > Regular > Games > Game
Breadcrumbs::for('personal.tournament.regular.game', function ($trail, PersonalGameRegular $game) {
    $trail->parent('personal.tournament.regular.games', $game->tournament);
    $trail->push(
        'Тур ' . $game->round . ': ' . $game->homePlayer->name . ' (' . $game->homePlayer->tag . ') vs. ' . $game->awayPlayer->name . ' (' . $game->awayPlayer->tag . ')',
        route(
            'personal.tournament.regular.game',
            ['tournamentId' => $game->tournament->id, 'gameId' => $game->id]
        )
    );
});
//Personal > Tournament > Regular > VK
Breadcrumbs::for('personal.tournament.regular.schedule', function ($trail, PersonalTournament $tournament) {
    $trail->parent('personal.tournament.regular', $tournament);
    $trail->push(
        'Расписание ВК',
        route('personal.tournament.regular.schedule', ['tournamentId' => $tournament->id])
    );
});
//Personal > Tournament > Playoff
Breadcrumbs::for('personal.tournament.playoff', function ($trail, PersonalTournament $tournament) {
    $trail->parent('personal.tournament', $tournament);
    $trail->push(
        'Плей-офф',
        route('personal.tournament.playoff', ['tournamentId' => $tournament->id])
    );
});
//Personal > Tournament > Playoff > Game
Breadcrumbs::for('personal.tournament.playoff.game', function ($trail, PersonalGamePlayoff $game) {
    $trail->parent('personal.tournament.playoff', $game->tournament);
    $roundText = TextUtils::playoffRound($game->tournament, $game->playoffPair->round);
    $pairText = strstr($roundText, 'финала') ? ' (пара ' . $game->playoffPair->pair . ')' : '';
    $trail->push(
        $roundText . $pairText . ': ' . $game->homePlayer->name . ' (' . $game->homePlayer->tag . ') vs. ' . $game->awayPlayer->name . ' (' . $game->awayPlayer->tag . ')',
        route(
            'personal.tournament.playoff.game',
            ['tournamentId' => $game->tournament->id, 'pairId' => $game->playoff_pair_id, 'gameId' => $game->id]
        )
    );
});
//Personal > Tournament > Playoff > Games
Breadcrumbs::for('personal.tournament.playoff.games', function ($trail, PersonalTournament $tournament) {
    $trail->parent('personal.tournament.playoff', $tournament);
    $trail->push(
        'Расписание',
        route(
            'personal.tournament.playoff.games',
            ['tournamentId' => $tournament->id]
        )
    );
});
//Personal > Tournament > Playoff > Games > New Game
Breadcrumbs::for('personal.tournament.playoff.game.add', function ($trail, PersonalTournamentPlayoff $pair) {
    $trail->parent('personal.tournament.playoff.games', $pair->tournament);
    $roundText = TextUtils::playoffRound($pair->tournament, $pair->round);
    $pairText = strstr($roundText, 'финала') ? ' (пара ' . $pair->pair . ')' : '';
    $trail->push(
        $roundText . $pairText,
        route(
            'personal.tournament.playoff.game.add',
            ['tournamentId' => $pair->tournament->id, 'pairId' => $pair->id]
        )
    );
});
//Players
Breadcrumbs::for('players', function ($trail) {
    $trail->push('Игроки', route('players'));
});
//Players > New
Breadcrumbs::for('player.add', function ($trail) {
    $trail->parent('players');
    $trail->push('Добавить игрока', route('player.add'));
});
//Players > Player
Breadcrumbs::for('player', function ($trail, Player $player) {
    $trail->parent('players');
    $trail->push($player->name . ' (' . $player->tag . ')', route('player', ['playerId' => $player->id]));
});
//Players > Player > Edit
Breadcrumbs::for('player.edit', function ($trail, Player $player) {
    $trail->parent('player', $player);
    $trail->push('Редактировать', route('player.edit', ['playerId' => $player->id]));
});
