<?php

namespace App\Http\Controllers\Site;

use App\Models\PersonalGameRegular;
use App\Models\PersonalTournament;
use App\Models\PersonalTournamentPosition;
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
        $positions = self::_getPosition($currentPosition, $previousPosition);

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

    /**
     * @param $prevPlace
     *
     * @return string
     */
    private static function _getPrevPlace($prevPlace)
    : string
    {
        if ($prevPlace !== '—' && $prevPlace > 0) {
            return "<span class='text-success text-nowrap'>$prevPlace<i class='fas fa-long-arrow-alt-up'></i></span>";
        } elseif ($prevPlace === 0) {
            return '<i class="fas fa-arrows-alt-h"></i>';
        } elseif ($prevPlace < 0) {
            $prevPlace = str_replace('-', '', $prevPlace);
            return "<span class='text-danger text-nowrap'>$prevPlace<i class='fas fa-long-arrow-alt-down'></i></span>";
        }
        return '<span class="text-primary"><i class="fas fa-arrow-right"></i></span>';
    }

    /**
     * @param array      $currentPosition
     * @param array|null $previousPosition
     *
     * @return array
     * @throws Exception
     */
    private static function _getPosition(array $currentPosition, array $previousPosition = null)
    : array
    {
        $previousPlaces = [];
        if (!is_null($previousPosition)) {
            $ppc = count($previousPosition);
            for ($i = 0; $i < $ppc; $i += 1) {
                if (!isset($previousPlaces[$previousPosition[$i]->id])) {
                    $previousPlaces[$previousPosition[$i]->division][] = $previousPosition[$i]->id;
                }
            }
        }

        $currentPlaces = [];
        $cpc = count($currentPosition);
        for ($i = 0; $i < $cpc; $i += 1) {
            if (!isset($currentPlaces[$currentPosition[$i]->id])) {
                $currentPlaces[$currentPosition[$i]->division][] = $currentPosition[$i]->id;
            }
        }
        $position = [];
        for ($i = 0; $i < $cpc; $i += 1) {
            $player = $currentPosition[$i];
            $goalsDif = $player->goals - $player->goals_against;

            $prevPlace = '—';
            if (
                isset($previousPlaces[$player->division])
                && in_array($player->id, $previousPlaces[$player->division])
            ) {
                $prevPlace = (array_search($player->id, $previousPlaces[$player->division]) + 1)
                    - (array_search($player->id, $currentPlaces[$player->division]) + 1);
            }
            $position[] = (object)[
                'place'                  => array_search($player->id, $currentPlaces[$player->division]) + 1,
                'prevPlace'              => self::_getPrevPlace($prevPlace),
                'id'                     => $player->id,
                'player'                 => $player->player,
                'division'               => $player->division,
                'games'                  => $player->games,
                'points'                 => $player->points,
                'wins'                   => $player->wins,
                'wins_ot'                => $player->wins_ot,
                'wins_so'                => $player->wins_so,
                'lose_ot'                => $player->lose_ot,
                'lose_so'                => $player->lose_so,
                'lose'                   => $player->lose,
                'goals_diff'             => $goalsDif > 0 ? '+' . $goalsDif : $goalsDif,
                'goals'                  => $player->goals,
                'goals_per_game'         => $player->games > 0
                    ? round($player->goals / $player->games, 2)
                    : 0.00,
                'goals_against_per_game' => $player->games > 0
                    ? round($player->goals_against / $player->games, 2)
                    : 0.00,
            ];
        }

        return $position;
    }
}
