<?php

namespace App\Http\Controllers\Site;

use App\Models\GroupGamePlayoff;
use App\Models\GroupTournament;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

/**
 * Class GroupPlayoffController
 * @package App\Http\Controllers\Site
 */
class GroupPlayoffController extends Controller
{
    /**
     * @param Request $request
     * @param int     $tournamentId
     * @return Factory|View
     */
    public function index(Request $request, int $tournamentId)
    {
        /** @var GroupTournament $tournament */
        $tournament = GroupTournament::find($tournamentId);
        if (is_null($tournament)) {
            abort(404);
        }

        $maxTeams = pow(2, $tournament->playoff_rounds);
        $bracket = [];
        for ($i = 1; $i <= $tournament->playoff_rounds; $i += 1) {
            for ($j = 1; $j <= $maxTeams / pow(2, $i); $j += 1) {
                $bracket[$i][$j] = null;
            }
        }
        foreach ($tournament->playoff as $playoff) {
            $bracket[$playoff->round][$playoff->pair] = $playoff;
        }

        return view('site.group.playoff.index', [
            'tournament' => $tournament,
            'maxTeams'   => $maxTeams,
            'bracket'    => $bracket,
        ]);
    }

    /**
     * @param Request $request
     * @param int     $tournamentId
     * @param int     $gameId
     * @return Factory|View
     */
    public function game(Request $request, int $tournamentId, int $gameId)
    {
        /** @var GroupGamePlayoff $game */
        $game = GroupGamePlayoff::with(['protocols.player', 'homeTeam.team', 'awayTeam.team'])
            ->find($gameId);
        if (is_null($game) || $game->playoffPair->tournament_id !== $tournamentId) {
            abort(404);
        }

        dd($game->protocols);
        foreach ($game->protocols as $protocol) {
            if ($protocol->team_id === $game->home_team_id) {
                $game->homeProtocols[] = $protocol;
                if ($protocol->isGoalie) {
                    $game->homeGoalie = $protocol;
                }
            } else {
                $game->awayProtocols[] = $protocol;
                if ($protocol->isGoalie) {
                    $game->awayGoalie = $protocol;
                }
            }
        }

        return view('site.group.game_protocol', [
            'game'       => $game,
            'tournament' => $game->playoffPair->tournament,
        ]);
    }
}
