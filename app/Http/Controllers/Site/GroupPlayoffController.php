<?php

namespace App\Http\Controllers\Site;

use App\Models\GroupGamePlayoff;
use App\Models\GroupTournament;
use App\Models\GroupTournamentPlayoff;
use App\Models\GroupTournamentPlayoffGoalies;
use App\Models\GroupTournamentPlayoffLeaders;
use App\Models\GroupTournamentPlayoffPosition;
use App\Models\PlayerPosition;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use TextUtils;

/**
 * Class GroupPlayoffController
 * @package App\Http\Controllers\Site
 */
class GroupPlayoffController extends Controller
{
    /**
     * @param Request         $request
     * @param GroupTournament $groupTournament
     * @return Factory|View
     */
    public function index(Request $request, GroupTournament $groupTournament)
    {
        $groupTournament->load(['playoff.teamOne', 'playoff.teamTwo', 'winners.team']);
        $bracket = [];
        $maxTeams = pow(2, $groupTournament->playoff_rounds + $groupTournament->thirdPlaceSeries);
        for ($i = 1; $i <= $groupTournament->playoff_rounds + $groupTournament->thirdPlaceSeries; $i += 1) {
            for ($j = 1; $j <= $maxTeams / pow(2, $i + $groupTournament->thirdPlaceSeries); $j += 1) {
                $bracket[$i][$j] = null;
            }
        }
        if ($groupTournament->thirdPlaceSeries) {
            $bracket[$groupTournament->playoff_rounds + $groupTournament->thirdPlaceSeries][1] = null;
        }
        foreach ($groupTournament->playoff as $playoff) {
            $bracket[$playoff->round][$playoff->pair] = $playoff;
        }

        return view('site.group.playoff.index', [
            'tournament' => $groupTournament,
            'bracket'    => $bracket,
        ]);
    }

    /**
     * @param Request                $request
     * @param GroupTournament        $groupTournament
     * @param GroupTournamentPlayoff $groupTournamentPlayoff
     * @param GroupGamePlayoff       $groupGamePlayoff
     * @return Factory|View
     */
    public function game(
        Request $request,
        GroupTournament $groupTournament,
        GroupTournamentPlayoff $groupTournamentPlayoff,
        GroupGamePlayoff $groupGamePlayoff
    ) {
        $groupGamePlayoff->load(['protocols.player', 'homeTeam.team', 'awayTeam.team']);
        if (
            $groupGamePlayoff->playoff_pair_id !== $groupTournamentPlayoff->id
            || $groupGamePlayoff->playoffPair->tournament_id !== $groupTournament->id
        ) {
            abort(404);
        }

        foreach ($groupGamePlayoff->protocols as $protocol) {
            if ($protocol->team_id === $groupGamePlayoff->home_team_id) {
                $groupGamePlayoff->homeProtocols[] = $protocol;
                if ($protocol->isGoalie) {
                    $groupGamePlayoff->homeGoalie = $protocol;
                }
            } else {
                $groupGamePlayoff->awayProtocols[] = $protocol;
                if ($protocol->isGoalie) {
                    $groupGamePlayoff->awayGoalie = $protocol;
                }
            }
        }

        $roundText = TextUtils::playoffRound(
            $groupGamePlayoff->playoffPair->tournament,
            $groupGamePlayoff->playoffPair->round
        );
        $pairText = strstr($roundText, 'финала') ? ' (пара ' . $groupGamePlayoff->playoffPair->pair . ')' : '';
        return view('site.group.game_protocol', [
            'title' => $groupGamePlayoff->homeTeam->team->name
                . ' vs. ' . $groupGamePlayoff->awayTeam->team->name . ' : ' . $roundText . $pairText,
            'game'  => $groupGamePlayoff,
            'stars' => $groupGamePlayoff->getStars(),
        ]);
    }

    /**
     * @param Request         $request
     * @param GroupTournament $groupTournament
     * @return Factory|View
     */
    public function stats(Request $request, GroupTournament $groupTournament)
    {
        $groupTournament->load(['winners.team']);
        $toDate = $request->input('toDate');

        $firstPlayedGameDate = GroupTournamentPlayoffPosition::readFirstGameDate($groupTournament->id);
        $dateToCompare = !is_null($toDate)
            ? $toDate . ' 00:00:00'
            : GroupTournamentPlayoffPosition::readLastGameDate($groupTournament->id);

        $currentPosition = GroupTournamentPlayoffPosition::readPosition($groupTournament->id);
        $previousPosition = null;
        if (!is_null($firstPlayedGameDate) && !is_null($dateToCompare) && $dateToCompare >= $firstPlayedGameDate) {
            $previousPosition = GroupTournamentPlayoffPosition::readPosition($groupTournament->id, $dateToCompare);
        }

        $currentLeaders = GroupTournamentPlayoffLeaders::readLeaders($groupTournament->id);
        $previousLeaders = null;
        if (!is_null($firstPlayedGameDate) && !is_null($dateToCompare) && $dateToCompare >= $firstPlayedGameDate) {
            $previousLeaders = GroupTournamentPlayoffLeaders::readLeaders($groupTournament->id, $dateToCompare);
        }
        $leaders = self::_getLeaders($currentLeaders, $previousLeaders);

        $currentGoalies = GroupTournamentPlayoffGoalies::readGoalies($groupTournament->id);
        $previousGoalies = null;
        if (!is_null($firstPlayedGameDate) && !is_null($dateToCompare) && $dateToCompare >= $firstPlayedGameDate) {
            $previousGoalies = GroupTournamentPlayoffGoalies::readGoalies($groupTournament->id, $dateToCompare);
        }
        $goalies = self::_getGoalies($currentGoalies, $currentPosition, $previousGoalies, $previousPosition);

        return view('site.group.playoff.stats', [
            'tournament'    => $groupTournament,
            'leaders'       => $leaders,
            'goalies'       => $goalies,
            'dateToCompare' => $dateToCompare,
        ]);
    }

    /**
     * @param Request         $request
     * @param GroupTournament $groupTournament
     *
     * @return Factory|View
     */
    public function games(Request $request, GroupTournament $groupTournament)
    {
        $groupTournament->load(['playoff.teamOne', 'playoff.teamTwo', 'winners.team']);
        $bracket = [];
        $maxTeams = pow(2, $groupTournament->playoff_rounds + $groupTournament->thirdPlaceSeries);
        for ($i = 1; $i <= $groupTournament->playoff_rounds + $groupTournament->thirdPlaceSeries; $i += 1) {
            for ($j = 1; $j <= $maxTeams / pow(2, $i + $groupTournament->thirdPlaceSeries); $j += 1) {
                $bracket[$i][$j] = null;
            }
        }
        if ($groupTournament->thirdPlaceSeries) {
            $bracket[$groupTournament->playoff_rounds + $groupTournament->thirdPlaceSeries][1] = null;
        }
        foreach ($groupTournament->playoff as $playoff) {
            $bracket[$playoff->round][$playoff->pair] = $playoff;
        }

        return view('site.group.playoff.games', [
            'tournament' => $groupTournament,
            'bracket'    => $bracket,
        ]);
    }

    /**
     * @param Request                $request
     * @param GroupTournament        $groupTournament
     * @param GroupTournamentPlayoff $groupTournamentPlayoff
     *
     * @return Factory|View
     */
    public function gameAdd(
        Request $request,
        GroupTournament $groupTournament,
        GroupTournamentPlayoff $groupTournamentPlayoff
    ) {
        if ($groupTournamentPlayoff->tournament_id !== $groupTournament->id) {
            abort(404);
        }

        $view = !$groupTournamentPlayoff->team_one_id || !$groupTournamentPlayoff->team_two_id
            ? 'site.group.playoff.incomplete_pair_protocol'
            : 'site.group.game_form';

        //$players = $pair->getSafePlayersData();
        $positionsRaw = PlayerPosition::all();
        $positions = [];
        foreach ($positionsRaw as $position) {
            $positions[] = $position->getSafePosition();
        }

        $roundText = TextUtils::playoffRound($groupTournamentPlayoff->tournament, $groupTournamentPlayoff->round);
        $pairText = strstr($roundText, 'финала') ? ' (пара ' . $groupTournamentPlayoff->pair . ')' : '';
        return view($view, [
            'title'     => $roundText . $pairText,
            'pair'      => $groupTournamentPlayoff,
            'game'      => null,
            'protocols' => [],
            'players'   => null,
            'positions' => $positions,
        ]);
    }

    /**
     * @param Request                $request
     * @param GroupTournament        $groupTournament
     * @param GroupTournamentPlayoff $groupTournamentPlayoff
     * @param GroupGamePlayoff       $groupGamePlayoff
     *
     * @return Factory|View
     */
    public function gameEdit(
        Request $request,
        GroupTournament $groupTournament,
        GroupTournamentPlayoff $groupTournamentPlayoff,
        GroupGamePlayoff $groupGamePlayoff
    ) {
        $groupGamePlayoff->load([
            'protocols.player',
            'protocols.playerPosition',
            'homeTeam.team.players',
            'awayTeam.team.players',
        ]);
        if (
            $groupGamePlayoff->playoff_pair_id !== $groupTournamentPlayoff->id
            || $groupGamePlayoff->playoffPair->tournament_id !== $groupTournament->id
        ) {
            abort(404);
        }

        $protocols = $groupGamePlayoff->getSafeProtocols();
        $players = $groupGamePlayoff->getSafePlayersData();
        $positionsRaw = PlayerPosition::all();
        $positions = [];
        foreach ($positionsRaw as $position) {
            $positions[] = $position->getSafePosition();
        }

        $roundText = TextUtils::playoffRound(
            $groupGamePlayoff->playoffPair->tournament,
            $groupGamePlayoff->playoffPair->round
        );
        $pairText = strstr($roundText, 'финала') ? ' (пара ' . $groupGamePlayoff->playoffPair->pair . ')' : '';
        return view('site.group.game_form', [
            'title'     => $roundText . $pairText,
            'pair'      => $groupGamePlayoff->playoffPair,
            'game'      => $groupGamePlayoff,
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
        if (!is_null($previousGoalies) && !is_null($previousStats)) {
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
