<?php

namespace App\Widgets;

use App\Models\GroupGamePlayoff;
use App\Models\GroupGameRegular;
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
        $regularGames = GroupGameRegular::with(['homeTeam.team', 'awayTeam.team', 'tournament'])
            ->whereNotNull('playedAt')
            ->whereNull('deletedAt')
            ->orderByDesc('playedAt')
            ->take(10)
            ->get();
        $playoffGames = GroupGamePlayoff::with(['homeTeam.team', 'awayTeam.team', 'tournament', 'playoffPair'])
            ->whereNotNull('playedAt')
            ->whereNull('deletedAt')
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
