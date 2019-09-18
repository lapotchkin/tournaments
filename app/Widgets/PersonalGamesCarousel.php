<?php

namespace App\Widgets;

use App\Models\PersonalGamePlayoff;
use App\Models\PersonalGameRegular;
use App\Models\PersonalTournament;
use App\Models\PersonalTournamentPlayoff;
use Arrilot\Widgets\AbstractWidget;

/**
 * Class PersonalGamesCarousel
 * @package App\Widgets
 */
class PersonalGamesCarousel extends AbstractWidget
{
    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $deletedTournaments = PersonalTournament::onlyTrashed()->get();
        $deletedTournamentsIds = [];
        foreach ($deletedTournaments as $deletedTournament) {
            $deletedTournamentsIds[] = $deletedTournament->id;
        }
        $pairs = PersonalTournamentPlayoff::whereIn('tournament_id', $deletedTournamentsIds)->get();
        $deletedTournamentsPairsIds = [];
        foreach ($pairs as $pair) {
            $deletedTournamentsPairsIds[] = $pair->id;
        }

        $regularGames = PersonalGameRegular::with(['homePlayer', 'awayPlayer', 'tournament'])
            ->whereNotNull('playedAt')
            ->whereNotIn('tournament_id', $deletedTournamentsIds)
            ->orderByDesc('playedAt')
            ->take(10)
            ->get();
        $playoffGames = PersonalGamePlayoff::with(['homePlayer', 'awayPlayer', 'tournament', 'playoffPair'])
            ->whereNotNull('playedAt')
            ->orderByDesc('playedAt', 'desc')
            ->whereNotIn('playoff_pair_id', $deletedTournamentsPairsIds)
            ->take(10)
            ->get();
        $games = $regularGames->merge($playoffGames)
            ->sortByDesc('playedAt');

        return view('widgets.personal_games_carousel', [
            'games' => $games,
        ]);
    }
}
