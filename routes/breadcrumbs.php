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
use App\Models\Team;

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
        '<span><i class="fab fa-' . $tournament->platform->icon . '"></i> ' . $tournament->title . '</span>',
        route('group.tournament', ['groupTournament' => $tournament->id])
    );
});
//Group > Tournament > Editor
Breadcrumbs::for('group.tournament.edit', function ($trail, $tournament) {
    $trail->parent('group.tournament', $tournament);
    $trail->push(
        'Редактировать турнир',
        route('group.tournament.edit', ['groupTournament' => $tournament->id])
    );
});
//Group > Tournament > Team
Breadcrumbs::for('group.tournament.team', function ($trail, $tournamentTeam, $title) {
    $trail->parent('group.tournament', $tournamentTeam->tournament);
    $trail->push(
        $title,
        route(
            'group.tournament.team',
            ['groupTournament' => $tournamentTeam->tournament->id, 'team' => $tournamentTeam->team_id]
        )
    );
});
//Group > Tournament > VK
Breadcrumbs::for('group.tournament.copypaste', function ($trail, $tournament) {
    $trail->parent('group.tournament', $tournament);
    $trail->push(
        'Данные для ВК',
        route('group.tournament.copypaste', ['groupTournament' => $tournament->id])
    );
});
//Group > Tournament > Regular
Breadcrumbs::for('group.tournament.regular', function ($trail, $tournament) {
    $trail->parent('group.tournament', $tournament);
    $trail->push(
        'Чемпионат',
        route('group.tournament.regular', ['groupTournament' => $tournament->id])
    );
});
//Group > Tournament > Regular > Games
Breadcrumbs::for('group.tournament.regular.games', function ($trail, $tournament) {
    $trail->parent('group.tournament.regular', $tournament);
    $trail->push(
        'Расписание',
        route('group.tournament.regular.games', ['groupTournament' => $tournament->id])
    );
});
//Group > Tournament > Regular > Games > Game
Breadcrumbs::for('group.tournament.regular.game', function ($trail, $game) {
    $trail->parent('group.tournament.regular.games', $game->tournament);
    $trail->push(
        'Тур ' . $game->round . ': ' . $game->homeTeam->team->name . ' vs. ' . $game->awayTeam->team->name,
        route(
            'group.tournament.regular.game',
            ['groupTournament' => $game->tournament->id, 'groupGameRegular' => $game->id]
        )
    );
});
//Group > Tournament > Regular > VK
Breadcrumbs::for('group.tournament.regular.schedule', function ($trail, $tournament) {
    $trail->parent('group.tournament.regular', $tournament);
    $trail->push(
        'Расписание ВК',
        route('group.tournament.regular.schedule', ['groupTournament' => $tournament->id])
    );
});
//Group > Tournament > Playoff
Breadcrumbs::for('group.tournament.playoff', function ($trail, $tournament) {
    $trail->parent('group.tournament', $tournament);
    $trail->push(
        'Плей-офф',
        route('group.tournament.playoff', ['groupTournament' => $tournament->id])
    );
});
//Group > Tournament > Playoff > Stats
Breadcrumbs::for('group.tournament.playoff.stats', function ($trail, $tournament) {
    $trail->parent('group.tournament', $tournament);
    $trail->push(
        'Статистика',
        route('group.tournament.playoff.stats', ['groupTournament' => $tournament->id])
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
            ['groupTournament' => $game->tournament->id, 'groupTournamentPlayoff' => $game->playoff_pair_id, 'groupGamePlayoff' => $game->id]
        )
    );
});
//Group > Tournament > Playoff > Games
Breadcrumbs::for('group.tournament.playoff.games', function ($trail, $tournament) {
    $trail->parent('group.tournament.playoff', $tournament);
    $trail->push(
        'Расписание',
        route('group.tournament.playoff.games', ['groupTournament' => $tournament->id])
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
            ['groupTournament' => $pair->tournament->id, 'groupTournamentPlayoff' => $pair->id]
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
        '<span><i class="fab fa-' . $tournament->platform->icon . '"></i> ' . $tournament->title . '</span>',
        route('personal.tournament', ['personalTournament' => $tournament])
    );
});
//Personal > Tournament > Map
Breadcrumbs::for('personal.tournament.map', function ($trail, PersonalTournament $tournament) {
    $trail->parent('personal.tournament', $tournament);
    $trail->push(
        'Карта игроков',
        route('personal.tournament.map', ['personalTournament' => $tournament])
    );
});
//Personal > Tournament > Editor
Breadcrumbs::for('personal.tournament.edit', function ($trail, PersonalTournament $tournament) {
    $trail->parent('personal.tournament', $tournament);
    $trail->push(
        'Редактировать турнир',
        route('personal.tournament.edit', ['personalTournament' => $tournament])
    );
});
//Personal > Tournament > Player
Breadcrumbs::for('personal.tournament.player', function ($trail, PersonalTournamentPlayer $tournamentPlayer, $title) {
    $trail->parent('personal.tournament', $tournamentPlayer->tournament);
    $trail->push(
        $title,
        route(
            'personal.tournament.player',
            ['personalTournament' => $tournamentPlayer->tournament, 'player' => $tournamentPlayer->player]
        )
    );
});
//Personal > Tournament > VK
Breadcrumbs::for('personal.tournament.copypaste', function ($trail, PersonalTournament $tournament) {
    $trail->parent('personal.tournament', $tournament);
    $trail->push(
        'Данные для ВК',
        route('personal.tournament.copypaste', ['personalTournament' => $tournament])
    );
});
//Personal > Tournament > Regular
Breadcrumbs::for('personal.tournament.regular', function ($trail, PersonalTournament $tournament) {
    $trail->parent('personal.tournament', $tournament);
    $trail->push(
        'Чемпионат',
        route('personal.tournament.regular', ['personalTournament' => $tournament])
    );
});
//Personal > Tournament > Regular > Games
Breadcrumbs::for('personal.tournament.regular.games', function ($trail, PersonalTournament $tournament) {
    $trail->parent('personal.tournament.regular', $tournament);
    $trail->push(
        'Расписание',
        route('personal.tournament.regular.games', ['personalTournament' => $tournament])
    );
});
//Personal > Tournament > Regular > Games > Game
Breadcrumbs::for('personal.tournament.regular.game', function ($trail, PersonalGameRegular $game) {
    $trail->parent('personal.tournament.regular.games', $game->tournament);
    $trail->push(
        'Тур ' . $game->round . ': ' . $game->homePlayer->tag . ' vs. ' . $game->awayPlayer->tag,
        route(
            'personal.tournament.regular.game',
            ['personalTournament' => $game->tournament, 'personalGameRegular' => $game]
        )
    );
});
//Personal > Tournament > Regular > VK
Breadcrumbs::for('personal.tournament.regular.schedule', function ($trail, PersonalTournament $tournament) {
    $trail->parent('personal.tournament.regular', $tournament);
    $trail->push(
        'Расписание ВК',
        route('personal.tournament.regular.schedule', ['personalTournament' => $tournament])
    );
});
//Personal > Tournament > Playoff
Breadcrumbs::for('personal.tournament.playoff', function ($trail, PersonalTournament $tournament) {
    $trail->parent('personal.tournament', $tournament);
    $trail->push(
        'Плей-офф',
        route('personal.tournament.playoff', ['personalTournament' => $tournament])
    );
});
//Personal > Tournament > Playoff > Game
Breadcrumbs::for('personal.tournament.playoff.game', function ($trail, PersonalGamePlayoff $game) {
    $trail->parent('personal.tournament.playoff', $game->tournament);
    $roundText = TextUtils::playoffRound($game->tournament, $game->playoffPair->round);
    $pairText = strstr($roundText, 'финала') ? ' (пара ' . $game->playoffPair->pair . ')' : '';
    $trail->push(
        $roundText . $pairText . ': ' . $game->homePlayer->tag . ' vs. ' . $game->awayPlayer->tag,
        route(
            'personal.tournament.playoff.game',
            ['personalTournament' => $game->tournament, 'personalTournamentPlayoff' => $game->playoffPair, 'personalGamePlayoff' => $game]
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
            ['personalTournament' => $tournament]
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
            ['personalTournament' => $pair->tournament, 'personalTournamentPlayoff' => $pair]
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
    $trail->push($player->tag, route('player', ['player' => $player->id]));
});
//Players > Player > Edit
Breadcrumbs::for('player.edit', function ($trail, Player $player) {
    $trail->parent('player', $player);
    $trail->push('Редактировать', route('player.edit', ['player' => $player->id]));
});
//Teams
Breadcrumbs::for('teams', function ($trail) {
    $trail->push('Команды', route('teams'));
});
//Teams > New
Breadcrumbs::for('team.add', function ($trail) {
    $trail->parent('teams');
    $trail->push('Добавить команду', route('team.add'));
});
//Teams > Team
Breadcrumbs::for('team', function ($trail, Team $team) {
    $trail->parent('teams');
    $trail->push($team->name, route('team', ['team' => $team->id]));
});
//Teams > Team > Edit
Breadcrumbs::for('team.edit', function ($trail, Team $team) {
    $trail->parent('team', $team);
    $trail->push('Редактировать', route('team.edit', ['team' => $team->id]));
});

//Teams > Tracker
Breadcrumbs::for('tracker', function ($trail) {
    $trail->push('Трансферы', route('tracker'));
});
