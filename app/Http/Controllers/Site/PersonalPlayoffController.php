<?php

namespace App\Http\Controllers\Site;

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
 *
 * @package App\Http\Controllers\Site
 */
class PersonalPlayoffController extends Controller
{
    /**
     * @param Request            $request
     * @param PersonalTournament $personalTournament
     *
     * @return Factory|View
     */
    public function index(Request $request, PersonalTournament $personalTournament)
    {
        $view = 'site.personal.playoff.index';
        if (Route::currentRouteName() === 'personal.tournament.playoff.games') {
            if (!Auth::check() || !Auth::user()->isAdmin()) {
                abort(403);
            }
            $view = 'site.personal.playoff.games';
        }

        $bracket = [];
        $maxTeams = pow(2, $personalTournament->playoff_rounds + $personalTournament->thirdPlaceSeries);
        for ($i = 1; $i <= $personalTournament->playoff_rounds + $personalTournament->thirdPlaceSeries; $i += 1) {
            for ($j = 1; $j <= $maxTeams / pow(2, $i + $personalTournament->thirdPlaceSeries); $j += 1) {
                $bracket[$i][$j] = null;
            }
        }
        if ($personalTournament->thirdPlaceSeries) {
            $bracket[$personalTournament->playoff_rounds + $personalTournament->thirdPlaceSeries][1] = null;
        }
        foreach ($personalTournament->playoff as $playoff) {
            $bracket[$playoff->round][$playoff->pair] = $playoff;
        }

        return view($view, [
            'tournament' => $personalTournament,
            'bracket'    => $bracket,
        ]);
    }

    /**
     * @param Request                   $request
     * @param PersonalTournament        $personalTournament
     * @param PersonalTournamentPlayoff $personalTournamentPlayoff
     * @param PersonalGamePlayoff       $personalGamePlayoff
     *
     * @return Factory|View
     */
    public function game(
        Request                   $request,
        PersonalTournament        $personalTournament,
        PersonalTournamentPlayoff $personalTournamentPlayoff,
        PersonalGamePlayoff       $personalGamePlayoff
    )
    {
        $personalGamePlayoff->load(['homePlayer', 'awayPlayer']);
        if (
            $personalGamePlayoff->playoff_pair_id !== $personalTournamentPlayoff->id
            || $personalGamePlayoff->playoffPair->tournament_id !== $personalTournament->id
        ) {
            abort(404);
        }

        $roundText = TextUtils::playoffRound(
            $personalGamePlayoff->playoffPair->tournament,
            $personalGamePlayoff->playoffPair->round
        );
        $pairText = strstr($roundText, 'финала') ? ' (пара ' . $personalGamePlayoff->playoffPair->pair . ')' : '';
        return view('site.personal.game_protocol', [
            'title' => $personalGamePlayoff->homePlayer->name . ' vs. ' . $personalGamePlayoff->awayPlayer->name . ' : ' . $roundText . $pairText,
            'game'  => $personalGamePlayoff,
        ]);
    }

    /**
     * @param Request                   $request
     * @param PersonalTournament        $personalTournament
     * @param PersonalTournamentPlayoff $personalTournamentPlayoff
     *
     * @return Factory|View
     */
    public function gameAdd(
        Request                   $request,
        PersonalTournament        $personalTournament,
        PersonalTournamentPlayoff $personalTournamentPlayoff
    )
    {
        if ($personalTournamentPlayoff->tournament_id !== $personalTournament->id) {
            abort(404);
        }

        $view = !$personalTournamentPlayoff->player_one_id || !$personalTournamentPlayoff->player_two_id
            ? 'site.personal.playoff.incomplete_pair_protocol'
            : 'site.personal.game_form';

        $roundText = TextUtils::playoffRound($personalTournamentPlayoff->tournament, $personalTournamentPlayoff->round);
        $pairText = strstr($roundText, 'финала') ? ' (пара ' . $personalTournamentPlayoff->pair . ')' : '';
        return view($view, [
            'title' => $roundText . $pairText,
            'pair'  => $personalTournamentPlayoff,
            'game'  => null,
        ]);
    }

    /**
     * @param Request                   $request
     * @param PersonalTournament        $personalTournament
     * @param PersonalTournamentPlayoff $personalTournamentPlayoff
     * @param PersonalGamePlayoff       $personalGamePlayoff
     *
     * @return Factory|View
     */
    public function gameEdit(
        Request                   $request,
        PersonalTournament        $personalTournament,
        PersonalTournamentPlayoff $personalTournamentPlayoff,
        PersonalGamePlayoff       $personalGamePlayoff
    )
    {
        if (
            $personalGamePlayoff->playoff_pair_id !== $personalTournamentPlayoff->id
            || $personalGamePlayoff->playoffPair->tournament_id !== $personalTournament->id
        ) {
            abort(404);
        }

        $roundText = TextUtils::playoffRound(
            $personalGamePlayoff->playoffPair->tournament,
            $personalGamePlayoff->playoffPair->round
        );
        $pairText = strstr($roundText, 'финала') ? ' (пара ' . $personalGamePlayoff->playoffPair->pair . ')' : '';
        return view('site.personal.game_form', [
            'title' => $roundText . $pairText,
            'pair'  => $personalGamePlayoff->playoffPair,
            'game'  => $personalGamePlayoff,
        ]);
    }
}
