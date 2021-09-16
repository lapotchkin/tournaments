<?php

namespace App\Http\Controllers\Site;

use App\Models\PersonalGameRegular;
use App\Models\PersonalTournament;
use App\Models\PersonalTournamentPosition;
use App\Utils\TournamentResults;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

/**
 * Class PersonalRegularController
 *
 * @package App\Http\Controllers\Site
 */
class PersonalRegularController extends Controller
{
    /**
     * @param Request            $request
     * @param PersonalTournament $personalTournament
     *
     * @return Factory|View
     * @throws Exception
     */
    public function index(Request $request, PersonalTournament $personalTournament)
    {
        $toDate = $request->input('toDate');

        $firstPlayedGameDate = PersonalTournamentPosition::readFirstGameDate($personalTournament->id);
        $dateToCompare = !is_null($toDate)
            ? $toDate . ' 00:00:00'
            : PersonalTournamentPosition::readLastGameDate($personalTournament->id);

        $currentPosition = PersonalTournamentPosition::readPosition($personalTournament->id);
        $previousPosition = null;
        if (!is_null($firstPlayedGameDate) && !is_null($dateToCompare) && $dateToCompare >= $firstPlayedGameDate) {
            $previousPosition = PersonalTournamentPosition::readPosition($personalTournament->id, $dateToCompare);
        }
        $positions = TournamentResults::getPosition($currentPosition, $previousPosition);

        $divisions = [];
        foreach ($positions as $position) {
            $divisions[$position->division][] = $position;
        }

        return view('site.personal.regular.index', [
            'tournament'    => $personalTournament,
            'divisions'     => $divisions,
            'dateToCompare' => $dateToCompare,
        ]);
    }

    /**
     * @param Request            $request
     * @param PersonalTournament $personalTournament
     *
     * @return Factory|View
     */
    public function games(Request $request, PersonalTournament $personalTournament)
    {
        $personalTournament->load(['regularGames.homePlayer', 'regularGames.awayPlayer', 'winners.player']);
        $rounds = [];
        $divisions = [];
        foreach ($personalTournament->regularGames as $regularGame) {
            $division = $regularGame->homePlayer->getDivision($personalTournament->id);
            if (!in_array($division, $divisions)) {
                $divisions[] = $division;
            }
            $rounds[$regularGame->round][$division][] = $regularGame;
        }

        return view('site.personal.regular.games', [
            'tournament' => $personalTournament,
            'rounds'     => $rounds,
            'divisions'  => $divisions,
        ]);
    }

    /**
     * @param Request             $request
     * @param PersonalTournament  $personalTournament
     * @param PersonalGameRegular $personalGameRegular
     *
     * @return Factory|View
     */
    public function game(
        Request             $request,
        PersonalTournament  $personalTournament,
        PersonalGameRegular $personalGameRegular
    )
    {
        $personalGameRegular->load(['homePlayer', 'awayPlayer']);
        if ($personalGameRegular->tournament_id !== $personalTournament->id) {
            abort(404);
        }

        return view('site.personal.game_protocol', [
            'title' => $personalGameRegular->homePlayer->name . ' vs. ' . $personalGameRegular->awayPlayer->name . ' (Тур ' . $personalGameRegular->round . ')',
            'game'  => $personalGameRegular,
        ]);
    }

    /**
     * @param Request             $request
     * @param PersonalTournament  $personalTournament
     * @param PersonalGameRegular $personalGameRegular
     *
     * @return Factory|View
     */
    public function gameEdit(
        Request             $request,
        PersonalTournament  $personalTournament,
        PersonalGameRegular $personalGameRegular
    )
    {
        $personalGameRegular->load(['homePlayer', 'awayPlayer']);
        if ($personalGameRegular->tournament_id !== $personalTournament->id) {
            abort(404);
        }

        return view('site.personal.game_form', [
            'title' => $personalGameRegular->homePlayer->name . ' vs. ' . $personalGameRegular->awayPlayer->name . ' (Тур ' . $personalGameRegular->round . ')',
            'pair'  => null,
            'game'  => $personalGameRegular,
        ]);
    }

    /**
     * @param Request            $request
     * @param PersonalTournament $personalTournament
     *
     * @return Factory|View
     */
    public function schedule(Request $request, PersonalTournament $personalTournament)
    {
        $personalTournament->load(['regularGames.homePlayer', 'regularGames.awayPlayer', 'winners.player']);
        $rounds = [];
        foreach ($personalTournament->regularGames as $regularGame) {
            $rounds[$regularGame->round][$regularGame->homePlayer->getDivision($personalTournament->id)][] = $regularGame;
        }

        return view('site.personal.regular.schedule', [
            'tournament' => $personalTournament,
            'rounds'     => $rounds,
        ]);
    }
}
