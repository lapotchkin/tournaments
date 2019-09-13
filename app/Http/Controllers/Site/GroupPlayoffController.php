<?php

namespace App\Http\Controllers\Site;

use App\Models\GroupGamePlayoff;
use App\Models\GroupTournament;
use App\Models\GroupTournamentPlayoff;
use App\Models\GroupTournamentPlayoffGoalies;
use App\Models\GroupTournamentPlayoffLeaders;
use App\Models\GroupTournamentPlayoffPosition;
use App\Models\PlayerPosition;
use App\Utils\TextUtils;
use Auth;
use Exception;
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
        $tournament = GroupTournament::with(['playoff.teamOne', 'playoff.teamTwo', 'winners.team'])
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

        return view('site.group.playoff.index', [
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
        /** @var GroupGamePlayoff $game */
        $game = GroupGamePlayoff::with(['protocols.player', 'homeTeam.team', 'awayTeam.team'])
            ->find($gameId);
        if (is_null($game) || $game->playoff_pair_id !== $pairId || $game->playoffPair->tournament_id !== $tournamentId) {
            abort(404);
        }

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

        $roundText = TextUtils::playoffRound($game->playoffPair->tournament, $game->playoffPair->round);
        $pairText = strstr($roundText, 'финала') ? ' (пара ' . $game->playoffPair->pair . ')' : '';
        return view('site.group.game_protocol', [
            'title' => $game->homeTeam->team->name . ' vs. ' . $game->awayTeam->team->name . ' : ' . $roundText . $pairText,
            'game'  => $game,
        ]);
    }

    /**
     * @param Request $request
     * @param int     $tournamentId
     * @return Factory|View
     * @throws Exception
     */
    public function stats(Request $request, int $tournamentId)
    {
        /** @var GroupTournament $tournament */
        $tournament = GroupTournament::with(['winners.team'])
            ->find($tournamentId);
        if (is_null($tournament)) {
            abort(404);
        }

        $lastUpdateDate = GroupTournamentPlayoffPosition::readLastUpdateDate($tournamentId);
        $currentPosition = GroupTournamentPlayoffPosition::readPosition($tournamentId);
        $previousPosition = null;
        if (!is_null($lastUpdateDate)) {
            $previousPosition = GroupTournamentPlayoffPosition::readPosition($tournamentId, $lastUpdateDate->date);
        }

        $currentLeaders = GroupTournamentPlayoffLeaders::readLeaders($tournamentId);
        $previousLeaders = null;
        if (!is_null($lastUpdateDate)) {
            $previousLeaders = GroupTournamentPlayoffLeaders::readLeaders($tournamentId, $lastUpdateDate->date);
        }
        $leaders = self::_getLeaders($currentLeaders, $previousLeaders);

        $currentGoalies = GroupTournamentPlayoffGoalies::readGoalies($tournamentId);
        $previousGoalies = null;
        if (!is_null($lastUpdateDate)) {
            $previousGoalies = GroupTournamentPlayoffGoalies::readGoalies($tournamentId, $lastUpdateDate->date);
        }
        $goalies = self::_getGoalies($currentGoalies, $currentPosition, $previousGoalies, $previousPosition);

        return view('site.group.playoff.stats', [
            'tournament' => $tournament,
            'leaders'    => $leaders,
            'goalies'    => $goalies,
        ]);
    }

    /**
     * @param Request $request
     * @param int     $tournamentId
     * @return Factory|View
     */
    public function games(Request $request, int $tournamentId)
    {
        if (!Auth::check()) {
            abort(403);
        }

        /** @var GroupTournament $tournament */
        $tournament = GroupTournament::with(['playoff.teamOne', 'playoff.teamTwo', 'winners.team'])
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

        return view('site.group.playoff.games', [
            'tournament' => $tournament,
            'bracket'    => $bracket,
        ]);
    }

    /**
     * @param Request $request
     * @param int     $tournamentId
     * @param int     $pairId
     * @return Factory|View
     */
    public function gameAdd(Request $request, int $tournamentId, int $pairId)
    {
        if (!Auth::check()) {
            abort(403);
        }

        /** @var GroupTournamentPlayoff $pair */
        $pair = GroupTournamentPlayoff::find($pairId);
        if (is_null($pair) || $pair->tournament_id !== $tournamentId) {
            abort(404);
        }

        $view = !$pair->team_one_id || !$pair->team_two_id
            ? 'site.group.playoff.incomplete_pair_protocol'
            : 'site.group.game_form';

        //$players = $pair->getSafePlayersData();
        $positionsRaw = PlayerPosition::all();
        $positions = [];
        foreach ($positionsRaw as $position) {
            $positions[] = $position->getSafePosition();
        }

        $roundText = TextUtils::playoffRound($pair->tournament, $pair->round);
        $pairText = strstr($roundText, 'финала') ? ' (пара ' . $pair->pair . ')' : '';
        return view($view, [
            'title'     => $roundText . $pairText,
            'pair'      => $pair,
            'game'      => null,
            'protocols' => [],
            'players'   => null,
            'positions' => $positions,
        ]);
    }

    public function gameEdit(Request $request, int $tournamentId, int $pairId, int $gameId)
    {
        if (!Auth::check()) {
            abort(403);
        }

        /** @var GroupGamePlayoff $game */
        $game = GroupGamePlayoff::with([
            'protocols.player',
            'protocols.playerPosition',
            'homeTeam.team.players.player',
            'awayTeam.team.players.player',
        ])
            ->find($gameId);
        if (is_null($game) || $game->playoff_pair_id !== $pairId || $game->playoffPair->tournament_id !== $tournamentId) {
            abort(404);
        }

        $protocols = $game->getSafeProtocols();
        $players = $game->getSafePlayersData();
        $positionsRaw = PlayerPosition::all();
        $positions = [];
        foreach ($positionsRaw as $position) {
            $positions[] = $position->getSafePosition();
        }

        $roundText = TextUtils::playoffRound($game->playoffPair->tournament, $game->playoffPair->round);
        $pairText = strstr($roundText, 'финала') ? ' (пара ' . $game->playoffPair->pair . ')' : '';
        return view('site.group.game_form', [
            'title'     => $roundText . $pairText,
            'pair'      => $game->playoffPair,
            'game'      => $game,
            'protocols' => $protocols,
            'players'   => $players,
            'positions' => $positions,
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
     * @param array      $currentLeaders
     * @param array|null $previousLeaders
     * @return object
     */
    private static function _getLeaders(array $currentLeaders, array $previousLeaders = null)
    {
        $order = [
            'points'  => ['points', 'goals', 'games'],
            'goals'   => ['goals', 'points', 'games'],
            'assists' => ['assists', 'points', 'games'],
        ];

        $leaders = (object)[];
        foreach ($order as $key => $sort) {
            //так сделано, потому что иначе позиция ставится по последней сорнтировке
            $leaders->{$key} = json_decode(json_encode($currentLeaders));
            array_multisort(
                array_column($leaders->{$key}, $sort[0]),
                SORT_DESC,
                array_column($leaders->{$key}, $sort[1]),
                SORT_DESC,
                array_column($leaders->{$key}, $sort[2]),
                SORT_ASC,
                $leaders->{$key}
            );

            $previousPlaces = [];
            if (!is_null($previousLeaders)) {
                array_multisort(
                    array_column($previousLeaders, $sort[0]),
                    SORT_DESC,
                    array_column($previousLeaders, $sort[1]),
                    SORT_DESC,
                    array_column($previousLeaders, $sort[2]),
                    SORT_ASC,
                    $previousLeaders
                );

                $ppc = count($previousLeaders);
                for ($i = 0; $i < $ppc; $i += 1) {
                    if (!isset($previousPlaces[$previousLeaders[$i]->id])) {
                        $previousPlaces[$previousLeaders[$i]->id] = $i;
                    }
                }
            }

            $pc = count($leaders->{$key});
            for ($i = 0; $i < $pc; $i += 1) {
                $prevPlace = isset($previousPlaces[$leaders->{$key}[$i]->id])
                    ? ($previousPlaces[$leaders->{$key}[$i]->id] + 1) - ($i + 1)
                    : '—';
                $leaders->{$key}[$i]->place = $i + 1;
                $leaders->{$key}[$i]->prevPlace = self::_getPrevPlace($prevPlace);
            }
        }

        return $leaders;
    }

    /**
     * @param array      $currentGoalies
     * @param array|null $previousGoalies
     * @param array      $currentStats
     * @param            $previousStats
     * @return array
     */
    private static function _getGoalies(
        array $currentGoalies,
        $currentStats,
        array $previousGoalies = null,
        $previousStats = null
    ) {
        $currentGames = [];
        foreach ($currentStats as $stat) {
            $currentGames[$stat->id] = $stat->games;
        }

        $previousPlaces = [];
        if (!is_null($previousGoalies)) {
            $previousGames = [];
            foreach ($previousStats as $stat) {
                $previousGames[$stat->id] = $stat->games;
            }
            $prev = [];
            foreach ($previousGoalies as $goalie) {
                if (!$previousGames[$goalie->team_id] || $goalie->games / $previousGames[$goalie->team_id] <= 0.25) {
                    continue;
                }
                $prev[] = $goalie;
                $goalie->saves_percent = round(
                    ($goalie->shot_against - $goalie->goal_against) / $goalie->shot_against,
                    3
                );
            }
            array_multisort(
                array_column($prev, 'saves_percent'),
                SORT_DESC,
                array_column($prev, 'games'),
                SORT_ASC,
                array_column($prev, 'shootouts'),
                SORT_DESC,
                $prev
            );
            $pc = count($prev);
            for ($i = 0; $i < $pc; $i += 1) {
                if (!isset($previousPlaces[$prev[$i]->id])) {
                    $previousPlaces[$prev[$i]->id] = $i;
                }
            }
        }

        $goalies = [];
        foreach ($currentGoalies as $goalie) {
            if ($goalie->games / $currentGames[$goalie->team_id] <= 0.25) {
                continue;
            }
            $goalies[] = $goalie;
            $goalie->loses = $goalie->games - $goalie->wins;
            $goalie->saves = $goalie->shot_against - $goalie->goal_against;
            $goalie->saves_percent = round(
                ($goalie->shot_against - $goalie->goal_against) / $goalie->shot_against,
                3
            );
            $goalie->goal_against_per_game = round($goalie->goal_against / $goalie->games, 2);
        }

        array_multisort(
            array_column($goalies, 'saves_percent'),
            SORT_DESC,
            array_column($goalies, 'games'),
            SORT_ASC,
            array_column($goalies, 'shootouts'),
            SORT_DESC,
            $goalies
        );
        $place = 1;
        foreach ($goalies as $goalie) {
            $prevPlace = isset($previousPlaces[$goalie->id])
                ? ($previousPlaces[$goalie->id] + 1) - ($place)
                : '—';
            $goalie->place = $place;
            $goalie->prevPlace = self::_getPrevPlace($prevPlace);
            $place += 1;
        }

        return $goalies;
    }
}
