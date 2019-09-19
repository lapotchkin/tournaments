<?php

namespace App\Http\Controllers\Site;

use App\Http\Requests\StoreRequest;
use App\Models\PersonalGamePlayoff;
use App\Models\PersonalTournament;
use App\Models\PersonalTournamentPlayoff;
use Auth;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Route;
use TextUtils;

/**
 * Class PersonalPlayoffController
 * @package App\Http\Controllers\Site
 */
class PersonalPlayoffController extends Controller
{
    /**
     * @param Request $request
     * @param int     $tournamentId
     * @return Factory|View
     */
    public function index(Request $request, int $tournamentId)
    {
        $view = 'site.personal.playoff.index';
        if (Route::currentRouteName() === 'personal.tournament.playoff.games') {
            if (!Auth::check() || !Auth::user()->isAdmin()) {
                abort(403);
            }
            $view = 'site.personal.playoff.games';
        }

        /** @var PersonalTournament $tournament */
        $tournament = PersonalTournament::with(['playoff.playerOne', 'playoff.playerTwo', 'winners.player'])
            ->find($tournamentId);
        if (is_null($tournament)) {
            abort(404);
        }

        $bracket = [];
        $maxTeams = pow(2, $tournament->playoff_rounds + $tournament->thirdPlaceSeries);
        for ($i = 1; $i <= $tournament->playoff_rounds + $tournament->thirdPlaceSeries; $i += 1) {
            for ($j = 1; $j <= $maxTeams / pow(2, $i + $tournament->thirdPlaceSeries); $j += 1) {
                $bracket[$i][$j] = null;
            }
        }
        if ($tournament->thirdPlaceSeries) {
            $bracket[$tournament->playoff_rounds + $tournament->thirdPlaceSeries][1] = null;
        }
        foreach ($tournament->playoff as $playoff) {
            $bracket[$playoff->round][$playoff->pair] = $playoff;
        }

        return view($view, [
            'tournament' => $tournament,
            'bracket'    => $bracket,
        ]);
    }

    /**
     * @param Request $request
     * @param int     $tournamentId
     * @param int     $pairId
     * @param int     $gameId
     * @return Factory|View
     */
    public function game(Request $request, int $tournamentId, int $pairId, int $gameId)
    {
        /** @var PersonalGamePlayoff $game */
        $game = PersonalGamePlayoff::with(['homePlayer', 'awayPlayer'])
            ->find($gameId);
        if (is_null($game) || $game->playoff_pair_id !== $pairId || $game->playoffPair->tournament_id !== $tournamentId) {
            abort(404);
        }

        $roundText = TextUtils::playoffRound($game->playoffPair->tournament, $game->playoffPair->round);
        $pairText = strstr($roundText, 'финала') ? ' (пара ' . $game->playoffPair->pair . ')' : '';
        return view('site.personal.game_protocol', [
            'title' => $game->homePlayer->name . ' vs. ' . $game->awayPlayer->name . ' : ' . $roundText . $pairText,
            'game'  => $game,
        ]);
    }

    /**
     * @param StoreRequest $request
     * @param int          $tournamentId
     * @param int          $pairId
     * @return Factory|View
     */
    public function gameAdd(StoreRequest $request, int $tournamentId, int $pairId)
    {
        /** @var PersonalTournamentPlayoff $pair */
        $pair = PersonalTournamentPlayoff::find($pairId);
        if (is_null($pair) || $pair->tournament_id !== $tournamentId) {
            abort(404);
        }

        $view = !$pair->player_one_id || !$pair->player_two_id
            ? 'site.personal.playoff.incomplete_pair_protocol'
            : 'site.personal.game_form';

        $roundText = TextUtils::playoffRound($pair->tournament, $pair->round);
        $pairText = strstr($roundText, 'финала') ? ' (пара ' . $pair->pair . ')' : '';
        return view($view, [
            'title' => $roundText . $pairText,
            'pair'  => $pair,
            'game'  => null,
        ]);
    }

    /**
     * @param StoreRequest $request
     * @param int          $tournamentId
     * @param int          $pairId
     * @param int          $gameId
     * @return Factory|View
     */
    public function gameEdit(StoreRequest $request, int $tournamentId, int $pairId, int $gameId)
    {
        /** @var PersonalGamePlayoff $game */
        $game = PersonalGamePlayoff::with(['homePlayer', 'awayPlayer'])
            ->find($gameId);
        if (is_null($game) || $game->playoff_pair_id !== $pairId || $game->playoffPair->tournament_id !== $tournamentId) {
            abort(404);
        }

        $roundText = TextUtils::playoffRound($game->playoffPair->tournament, $game->playoffPair->round);
        $pairText = strstr($roundText, 'финала') ? ' (пара ' . $game->playoffPair->pair . ')' : '';
        return view('site.personal.game_form', [
            'title' => $roundText . $pairText,
            'pair'  => $game->playoffPair,
            'game'  => $game,
        ]);
    }
}
