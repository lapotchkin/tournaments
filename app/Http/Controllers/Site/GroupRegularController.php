<?php

namespace App\Http\Controllers\Site;

use App\Http\Requests\StoreRequest;
use App\Models\GroupGameRegular;
use App\Models\GroupTournament;
use App\Models\GroupTournamentGoalies;
use App\Models\GroupTournamentLeaders;
use App\Models\GroupTournamentPosition;
use App\Models\PlayerPosition;
use DateInterval;
use DateTime;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

/**
 * Class GroupRegularController
 * @package App\Http\Controllers\Site
 */
class GroupRegularController extends Controller
{
    /**
     * @param Request $request
     * @param int     $tournamentId
     * @return Factory|View
     * @throws Exception
     */
    public function index(Request $request, int $tournamentId)
    {
        /** @var GroupTournament $tournament */
        $tournament = GroupTournament::with(['winners.team'])->find($tournamentId);
        if (is_null($tournament)) {
            abort(404);
        }
        $toDate = $request->input('toDate');

        $firstPlayedGameDate = GroupTournamentPosition::readFirstGameDate($tournamentId);
        $lastUpdateDate = !is_null($toDate)
            ? $toDate . ' 00:00:00'
            : GroupTournamentPosition::readLastUpdateDate($tournamentId);

        $currentPosition = GroupTournamentPosition::readPosition($tournamentId);
        $previousPosition = null;
        if (!is_null($firstPlayedGameDate) && !is_null($lastUpdateDate) && $lastUpdateDate > $firstPlayedGameDate) {
            $previousPosition = GroupTournamentPosition::readPosition($tournamentId, $lastUpdateDate);
        }
        $position = self::_getPosition($currentPosition, $previousPosition);

        $currentLeaders = GroupTournamentLeaders::readLeaders($tournamentId);
        $previousLeaders = null;
        if (!is_null($firstPlayedGameDate) && !is_null($lastUpdateDate) && $lastUpdateDate > $firstPlayedGameDate) {
            $previousLeaders = GroupTournamentLeaders::readLeaders($tournamentId, $lastUpdateDate);
        }
        $leaders = self::_getLeaders($currentLeaders, $previousLeaders);

        $currentGoalies = GroupTournamentGoalies::readGoalies($tournamentId);
        $previousGoalies = null;
        if (!is_null($firstPlayedGameDate) && !is_null($lastUpdateDate) && $lastUpdateDate > $firstPlayedGameDate) {
            $previousGoalies = GroupTournamentGoalies::readGoalies($tournamentId, $lastUpdateDate);
        }
        $goalies = self::_getGoalies($currentGoalies, $currentPosition, $previousGoalies, $previousPosition);

        return view('site.group.regular.index', [
            'tournament'     => $tournament,
            'position'       => $position,
            'leaders'        => $leaders,
            'goalies'        => $goalies,
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
        /** @var GroupTournament $tournament */
        $tournament = GroupTournament::with([
            'regularGames.homeTeam.team',
            'regularGames.awayTeam.team',
            'winners.team',
        ])
            ->find($tournamentId);
        if (is_null($tournament)) {
            abort(404);
        }

        $rounds = [];
        $divisions = [];
        foreach ($tournament->regularGames as $regularGame) {
            $division = $regularGame->homeTeam->division;
            if (!in_array($division, $divisions)) {
                $divisions[] = $division;
            }
            $rounds[$regularGame->round][$division][] = $regularGame;
        }

        return view('site.group.regular.games', [
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
        /** @var GroupGameRegular $game */
        $game = GroupGameRegular::with(['protocols.player', 'homeTeam.team', 'awayTeam.team'])
            ->find($gameId);
        if (is_null($game) || $game->tournament_id !== $tournamentId) {
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

        return view('site.group.game_protocol', [
            'title' => $game->homeTeam->team->name . ' vs. ' . $game->awayTeam->team->name . ' (Тур ' . $game->round . ')',
            'game'  => $game,
            'stars' => $game->getStars(),
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
        /** @var GroupGameRegular $game */
        $game = GroupGameRegular::with([
            'protocols.player',
            'protocols.playerPosition',
            'homeTeam.team.players',
            'awayTeam.team.players',
        ])
            ->find($gameId);
        if (is_null($game) || $game->tournament_id !== $tournamentId) {
            abort(404);
        }

        foreach ($game->protocols as $protocol) {
            if ($protocol->team_id === $game->home_team_id) {
                $game->homeProtocols[] = $protocol;
            } else {
                $game->awayProtocols[] = $protocol;
            }
        }
        $protocols = $game->getSafeProtocols();
        $players = $game->getSafePlayersData();
        $positionsRaw = PlayerPosition::all();
        $positions = [];
        foreach ($positionsRaw as $position) {
            $positions[] = $position->getSafePosition();
        }

        return view('site.group.game_form', [
            'title'     => $game->homeTeam->team->name . ' vs. ' . $game->awayTeam->team->name . ' (Тур ' . $game->round . ')',
            'pair'      => null,
            'game'      => $game,
            'protocols' => $protocols,
            'players'   => $players,
            'positions' => $positions,
        ]);
    }

    /**
     * @param Request $request
     * @param int     $tournamentId
     * @return Factory|View
     */
    public function schedule(Request $request, int $tournamentId)
    {
        /** @var GroupTournament $tournament */
        $tournament = GroupTournament::with([
            'regularGames.homeTeam.team',
            'regularGames.awayTeam.team',
            'winners.team',
        ])
            ->find($tournamentId);
        if (is_null($tournament)) {
            abort(404);
        }

        $rounds = [];
        foreach ($tournament->regularGames as $index => $regularGame) {
            if (
                $index > 0
                && (
                    $tournament->regularGames[$index - 1]->home_team_id === $tournament->regularGames[$index]->away_team_id
                    && $tournament->regularGames[$index - 1]->away_team_id === $tournament->regularGames[$index]->home_team_id
                )
            ) {
                $rounds[$regularGame->round][$regularGame->homeTeam->division][] = $regularGame;
            }
        }

        return view('site.group.regular.schedule', [
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
                    $previousPlaces[$previousPosition[$i]->id] = $i;
                }
            }
        }

        $position = [];
        $cpc = count($currentPosition);
        for ($i = 0; $i < $cpc; $i += 1) {
            $team = $currentPosition[$i];
            $goalsDif = $team->goals - $team->goals_against;
            $attackSec = $team->games > 0 ? round($team->attack_time / $team->games) : 0;
            $attackTime = new DateTime();
            $attackTime->setTime(0, 0, 0, 0);
            $attackTime->add(new DateInterval('PT' . $attackSec . 'S'));

            $prevPlace = isset($previousPlaces[$team->id]) ? ($previousPlaces[$team->id] + 1) - ($i + 1) : '—';
            $position[] = [
                'place'                  => $i + 1,
                'prevPlace'              => self::_getPrevPlace($prevPlace),
                'id'                     => $team->id,
                'team'                   => $team->team,
                'games'                  => $team->games,
                'points'                 => $team->points,
                'wins'                   => $team->wins,
                'wins_ot'                => $team->wins_ot,
                'wins_so'                => $team->wins_so,
                'lose_ot'                => $team->lose_ot,
                'lose_so'                => $team->lose_so,
                'lose'                   => $team->lose,
                'goals_diff'             => $goalsDif > 0 ? '+' . $goalsDif : $goalsDif,
                'goals'                  => $team->goals,
                'goals_per_game'         => $team->games > 0
                    ? round($team->goals / $team->games, 2)
                    : 0.00,
                'goals_against_per_game' => $team->games > 0
                    ? round($team->goals_against / $team->games, 2)
                    : 0.00,
                'powerplay'              => $team->penalty_for > 0
                    ? round($team->penalty_for_success / $team->penalty_for * 100, 1) . '%'
                    : '0.0%',
                'penalty_kill'           => $team->penalty_against > 0 ?
                    100 - round($team->penalty_against_success / $team->penalty_against * 100, 1) . '%'
                    : '0.0%',
                'shots_for'              => $team->shots_for,
                'shots_against'          => $team->shots_against,
                'shots_for_per_game'     => $team->games > 0
                    ? round($team->shots_for / $team->games, 1)
                    : 0.0,
                'shots_against_per_game' => $team->games > 0
                    ? round($team->shots_against / $team->games, 1)
                    : 0.0,
                'faceoff'                => round($team->faceoff, 1) . '%',
                'hit_for_per_game'       => $team->games > 0
                    ? round($team->hit_for / $team->games, 1)
                    : 0.0,
                'hit_against_per_game'   => $team->games > 0
                    ? round($team->hit_against / $team->games, 1)
                    : 0.0,
                'shorthanded_goal'       => $team->shorthanded_goal,
                'attack_time'            => $attackTime->format('i:s'),
                'pass_percent'           => round($team->pass_percent, 1) . '%',
            ];
        }

        return $position;
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
