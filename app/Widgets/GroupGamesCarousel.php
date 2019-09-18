<?php

namespace App\Widgets;

use App\Models\GroupGamePlayoff;
use App\Models\GroupGameRegular;
use App\Models\GroupTournament;
use Arrilot\Widgets\AbstractWidget;

/**
 * Class GroupGamesCarousel
 * @package App\Widgets
 */
class GroupGamesCarousel extends AbstractWidget
{
    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $deletedTournaments = GroupTournament::onlyTrashed()->get();
        $deletedTournamentsIds = [];
        foreach ($deletedTournaments as $deletedTournament) {
            $deletedTournamentsIds[] = $deletedTournament->id;
        }

        $regularGames = GroupGameRegular::with(['homeTeam.team', 'awayTeam.team', 'tournament'])
            ->whereNotNull('playedAt')
            ->whereNotIn('tournament_id', $deletedTournamentsIds)
            ->orderByDesc('playedAt')
            ->take(10)
            ->get();
        $playoffGames = GroupGamePlayoff::with(['homeTeam.team', 'awayTeam.team', 'tournament', 'playoffPair'])
            ->whereNotNull('playedAt')
            ->orderByDesc('playedAt', 'desc')
            ->take(10)
            ->get();
        $games = $regularGames->merge($playoffGames)
            ->sortByDesc('playedAt');

        return view('widgets.group_games_carousel', [
            'games' => $games,
        ]);
    }
}
