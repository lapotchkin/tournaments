<?php

namespace App\Http\Controllers\Site;

use App\Http\Requests\StoreRequest;
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
 * @package App\Http\Controllers\Site
 */
class PersonalRegularController extends Controller
{
    /**
     * @param Request $request
     * @param int     $tournamentId
     * @return Factory|View
     * @throws Exception
     */
    public function index(Request $request, int $tournamentId)
    {
        /** @var PersonalTournament $tournament */
        $tournament = PersonalTournament::with(['winners.player'])
            ->find($tournamentId);
        if (is_null($tournament)) {
            abort(404);
        }
        $toDate = $request->input('toDate');

        $lastUpdateDate = !is_null($toDate)
            ? $toDate . ' 00:00:00'
            : PersonalTournamentPosition::readLastUpdateDate($tournamentId);

        $currentPosition = PersonalTournamentPosition::readPosition($tournamentId);
        $previousPosition = null;
        if (!is_null($lastUpdateDate)) {
            $previousPosition = PersonalTournamentPosition::readPosition($tournamentId, $lastUpdateDate);
        }
        $positions = self::_getPosition($currentPosition, $previousPosition);

        $divisions = [];
        foreach ($positions as $position) {
            $divisions[$position->division][] = $position;
        }

        return view('site.personal.regular.index', [
            'tournament'     => $tournament,
            'divisions'      => $divisions,
            'lastUpdateDate' => $lastUpdateDate,
        ]);
    }

    /**
     * @param Request $request
     * @param int     $tournamentId
     * @return Factory|View
     */
    public function games(Request $request, int $tournamentId)
    {
        /** @var PersonalTournament $tournament */
        $tournament = PersonalTournament::with([
            'regularGames.homePlayer',
            'regularGames.awayPlayer',
            'winners.player',
        ])
            ->find($tournamentId);
        if (is_null($tournament)) {
            abort(404);
        }

        $rounds = [];
        $divisions = [];
        foreach ($tournament->regularGames as $regularGame) {
            $division = $regularGame->homePlayer->getDivision($tournamentId);
            if (!in_array($division, $divisions)) {
                $divisions[] = $division;
            }
            $rounds[$regularGame->round][$division][] = $regularGame;
        }

        return view('site.personal.regular.games', [
            'tournament' => $tournament,
            'rounds'     => $rounds,
            'divisions'  => $divisions,
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
        /** @var PersonalGameRegular $game */
        $game = PersonalGameRegular::with(['homePlayer', 'awayPlayer'])
            ->find($gameId);
        if (is_null($game) || $game->tournament_id !== $tournamentId) {
            abort(404);
        }

        return view('site.personal.game_protocol', [
            'title' => $game->homePlayer->name . ' vs. ' . $game->awayPlayer->name . ' (Тур ' . $game->round . ')',
            'game'  => $game,
        ]);
    }

    /**
     * @param StoreRequest $request
     * @param int          $tournamentId
     * @param int          $gameId
     * @return Factory|View
     */
    public function gameEdit(StoreRequest $request, int $tournamentId, int $gameId)
    {
        /** @var PersonalGameRegular $game */
        $game = PersonalGameRegular::with([
            'homePlayer',
            'awayPlayer',
        ])
            ->find($gameId);
        if (is_null($game) || $game->tournament_id !== $tournamentId) {
            abort(404);
        }

        return view('site.personal.game_form', [
            'title' => $game->homePlayer->name . ' vs. ' . $game->awayPlayer->name . ' (Тур ' . $game->round . ')',
            'pair'  => null,
            'game'  => $game,
        ]);
    }

    /**
     * @param Request $request
     * @param int     $tournamentId
     * @return Factory|View
     */
    public function schedule(Request $request, int $tournamentId)
    {
        /** @var PersonalTournament $tournament */
        $tournament = PersonalTournament::with([
            'regularGames.homePlayer',
            'regularGames.awayPlayer',
            'winners.player',
        ])
            ->find($tournamentId);
        if (is_null($tournament)) {
            abort(404);
        }

        $rounds = [];
        foreach ($tournament->regularGames as $index => $regularGame) {
            //if (
            //    $index > 0
            //    && (
            //        $tournament->regularGames[$index - 1]->home_player_id === $tournament->regularGames[$index]->away_player_id
            //        && $tournament->regularGames[$index - 1]->away_player_id === $tournament->regularGames[$index]->home_player_id
            //    )
            //) {
            $rounds[$regularGame->round][$regularGame->homePlayer->getDivision($tournamentId)][] = $regularGame;
            //}
        }

        return view('site.personal.regular.schedule', [
            'tournament' => $tournament,
            'rounds'     => $rounds,
        ]);
    }

    /**
     * @param $prevPlace
     * @return string
     */
    private static function _getPrevPlace($prevPlace)
    {
        if ($prevPlace !== '—' && $prevPlace > 0) {
            return "<span class='text-success'>{$prevPlace}<i class='fas fa-long-arrow-alt-up'></i></span>";
        } elseif ($prevPlace === 0) {
            return '<i class="fas fa-arrows-alt-h"></i>';
        } elseif ($prevPlace < 0) {
            $prevPlace = str_replace('-', '', $prevPlace);
            return "<span class='text-danger'>{$prevPlace}<i class='fas fa-long-arrow-alt-down'></i></span>";
        }
        return '<span class="text-primary"><i class="fas fa-arrow-right"></i></span>';
    }

    /**
     * @param array      $currentPosition
     * @param array|null $previousPosition
     * @return array
     * @throws Exception
     */
    private static function _getPosition(array $currentPosition, array $previousPosition = null)
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

        $cpc = count($currentPosition);
        $currentPlaces = [];
        for ($i = 0; $i < $cpc; $i += 1) {
            if (!isset($currentPlaces[$currentPosition[$i]->id])) {
                $currentPlaces[$currentPosition[$i]->division][] = $currentPosition[$i]->id;
            }
        }
        $position = [];
        for ($i = 0; $i < $cpc; $i += 1) {
            $player = $currentPosition[$i];
            $goalsDif = $player->goals - $player->goals_against;

            $prevPlace = isset($previousPlaces[$player->division]) && in_array($player->id,
                $previousPlaces[$player->division])
                ? (array_search($player->id, $previousPlaces[$player->division]) + 1)
                - (array_search($player->id, $currentPlaces[$player->division]) + 1)
                : '—';
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
