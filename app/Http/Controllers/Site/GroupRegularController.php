<?php

namespace App\Http\Controllers\Site;

use App\Models\EaGame;
use App\Models\GroupGameRegular;
use App\Models\GroupTournament;
use App\Models\GroupTournamentGoalies;
use App\Models\GroupTournamentLeaders;
use App\Models\GroupTournamentPosition;
use App\Models\PlayerPosition;
use TextUtils;
use DateInterval;
use DateTime;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

/**
 * Class GroupRegularController
 *
 * @package App\Http\Controllers\Site
 */
class GroupRegularController extends Controller
{
    /**
     * @param Request         $request
     * @param GroupTournament $groupTournament
     *
     * @return Factory|View
     * @throws Exception
     */
    public function index(Request $request, GroupTournament $groupTournament)
    {
        $groupTournament->load(['winners.team']);
        $toDate = $request->input('toDate');

        $firstPlayedGameDate = GroupTournamentPosition::readFirstGameDate($groupTournament->id);
        $dateToCompare = !is_null($toDate)
            ? $toDate . ' 00:00:00'
            : GroupTournamentPosition::readLastGameDate($groupTournament->id);

        $currentPosition = GroupTournamentPosition::readPosition($groupTournament->id);
        $previousPosition = null;
        if (!is_null($firstPlayedGameDate) && !is_null($dateToCompare) && $dateToCompare >= $firstPlayedGameDate) {
            $previousPosition = GroupTournamentPosition::readPosition($groupTournament->id, $dateToCompare);
        }
        $position = self::_getPosition($currentPosition, $previousPosition);

        $currentLeaders = GroupTournamentLeaders::readLeaders($groupTournament->id);
        $previousLeaders = null;
        if (!is_null($firstPlayedGameDate) && !is_null($dateToCompare) && $dateToCompare >= $firstPlayedGameDate) {
            $previousLeaders = GroupTournamentLeaders::readLeaders($groupTournament->id, $dateToCompare);
        }
        $leaders = self::_getLeaders($currentLeaders, $previousLeaders);

        $currentGoalies = GroupTournamentGoalies::readGoalies($groupTournament->id);
        $previousGoalies = null;
        if (!is_null($firstPlayedGameDate) && !is_null($dateToCompare) && $dateToCompare >= $firstPlayedGameDate) {
            $previousGoalies = GroupTournamentGoalies::readGoalies($groupTournament->id, $dateToCompare);
        }
        $goalies = self::_getGoalies($currentGoalies, $currentPosition, $previousGoalies, $previousPosition);

        return view('site.group.regular.index', [
            'tournament'    => $groupTournament,
            'position'      => $position,
            'leaders'       => $leaders,
            'goalies'       => $goalies['top'],
            'goaliesAll'    => $goalies['all'],
            'dateToCompare' => $dateToCompare,
        ]);
    }

    /**
     * @param Request         $request
     * @param GroupTournament $groupTournament
     *
     * @return Factory|View
     * @throws Exception
     */
    public function games(Request $request, GroupTournament $groupTournament)
    {
        $groupTournament->load(['regularGames.homeTeam.team', 'regularGames.awayTeam.team', 'winners.team']);
        $rounds = [];
        $divisions = [];
        foreach ($groupTournament->regularGames as $regularGame) {
            $division = $regularGame->homeTeam->division;
            if (!in_array($division, $divisions)) {
                $divisions[] = $division;
            }
            $rounds[$regularGame->round][$division][] = $regularGame;

            if (is_null($regularGame->match_id)) {
                $regularGame->gamePlayed = null;
                $game = EaGame::where(
                    'clubs.' . $regularGame->homeTeam->team->getClubId($groupTournament->app_id),
                    'exists',
                    true
                )
                    ->where(
                        'clubs.' . $regularGame->awayTeam->team->getClubId($groupTournament->app_id),
                        'exists',
                        true
                    )
                    ->where(
                        'timestamp',
                        '>',
                        $groupTournament->startedAt ? $groupTournament->startedAt->getTimestamp() : 0
                    )
                    ->orderByDesc('timestamp')
                    ->first();
                if (!is_null($game)) {
                    $date = new DateTime();
                    $date->setTimestamp($game->timestamp);
                    $regularGame->gamePlayed = $date->format('d.m H:i');
                }
            }
        }

        return view('site.group.regular.games', [
            'tournament' => $groupTournament,
            'rounds'     => $rounds,
            'divisions'  => $divisions,
        ]);
    }

    /**
     * @param Request          $request
     * @param GroupTournament  $groupTournament
     * @param GroupGameRegular $groupGameRegular
     *
     * @return Factory|View
     */
    public function game(Request $request, GroupTournament $groupTournament, GroupGameRegular $groupGameRegular)
    {
        $groupGameRegular->load(['protocols.player', 'homeTeam.team', 'awayTeam.team']);
        if ($groupGameRegular->tournament_id !== $groupTournament->id) {
            abort(404);
        }

        foreach ($groupGameRegular->protocols as $protocol) {
            if ($protocol->team_id === $groupGameRegular->home_team_id) {
                $groupGameRegular->homeProtocols[] = $protocol;
                if ($protocol->isGoalie) {
                    $groupGameRegular->homeGoalie = $protocol;
                }
            } else {
                $groupGameRegular->awayProtocols[] = $protocol;
                if ($protocol->isGoalie) {
                    $groupGameRegular->awayGoalie = $protocol;
                }
            }
        }

        return view('site.group.game_protocol', [
            'title' => $groupGameRegular->homeTeam->team->name
                . ' vs. ' . $groupGameRegular->awayTeam->team->name . ' (Тур ' . $groupGameRegular->round . ')',
            'game'  => $groupGameRegular,
            'stars' => $groupGameRegular->getStars(),
        ]);
    }

    /**
     * @param Request          $request
     * @param GroupTournament  $groupTournament
     * @param GroupGameRegular $groupGameRegular
     *
     * @return Factory|View
     */
    public function gameEdit(
        Request $request,
        GroupTournament $groupTournament,
        GroupGameRegular $groupGameRegular
    )
    {
        $groupGameRegular->load([
            'protocols.player',
            'protocols.playerPosition',
            'homeTeam.team.players',
            'awayTeam.team.players',
        ]);
        if ($groupGameRegular->tournament_id !== $groupTournament->id) {
            abort(404);
        }

        foreach ($groupGameRegular->protocols as $protocol) {
            if ($protocol->team_id === $groupGameRegular->home_team_id) {
                $groupGameRegular->homeProtocols[] = $protocol;
            } else {
                $groupGameRegular->awayProtocols[] = $protocol;
            }
        }
        $protocols = $groupGameRegular->getSafeProtocols();
        $players = $groupGameRegular->getSafePlayersData();
        $positionsRaw = PlayerPosition::all();
        $positions = [];
        foreach ($positionsRaw as $position) {
            $positions[] = $position->getSafePosition();
        }

        return view('site.group.game_form', [
            'title'     => $groupGameRegular->homeTeam->team->name
                . ' vs. ' . $groupGameRegular->awayTeam->team->name . ' (Тур ' . $groupGameRegular->round . ')',
            'pair'      => null,
            'game'      => $groupGameRegular,
            'protocols' => $protocols,
            'players'   => $players,
            'positions' => $positions,
        ]);
    }

    /**
     * @param Request         $request
     * @param GroupTournament $groupTournament
     *
     * @return Factory|View
     */
    public function schedule(Request $request, GroupTournament $groupTournament)
    {
        $groupTournament->load([
            'regularGames.homeTeam.team',
            'regularGames.awayTeam.team',
            'winners.team',
        ]);
        $rounds = [];
        foreach ($groupTournament->regularGames as $index => $regularGame) {
            if (
                $index > 0
                && (
                    $groupTournament->regularGames[$index - 1]->home_team_id === $groupTournament->regularGames[$index]->away_team_id
                    && $groupTournament->regularGames[$index - 1]->away_team_id === $groupTournament->regularGames[$index]->home_team_id
                )
            ) {
                $rounds[$regularGame->round][$regularGame->homeTeam->division][] = $regularGame;
            }
        }

        return view('site.group.regular.schedule', [
            'tournament' => $groupTournament,
            'rounds'     => $rounds,
        ]);
    }

    /**
     * @param mixed $prevPlace
     *
     * @return string
     */
    private static function _getPrevPlace($prevPlace)
    : string
    {
        if ($prevPlace !== '—' && $prevPlace > 0) {
            return "<span class='text-success text-nowrap'>{$prevPlace}<i class='fas fa-long-arrow-alt-up'></i></span>";
        } elseif ($prevPlace === 0) {
            return '<i class="fas fa-arrows-alt-h"></i>';
        } elseif ($prevPlace < 0) {
            $prevPlace = str_replace('-', '', $prevPlace);
            return "<span class='text-danger text-nowrap'>{$prevPlace}<i class='fas fa-long-arrow-alt-down'></i></span>";
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
     *
     * @return object
     */
    private static function _getLeaders(array $currentLeaders, array $previousLeaders = null)
    {
        $order = [
            'points'  => ['points', 'goals', 'games'],
            'goals'   => ['goals', 'points', 'games'],
            'assists' => ['assists', 'points', 'games'],
        ];

        foreach ($currentLeaders as &$player) {
            $player->position = '';

            if ($player->center_count > 0) {
                $player->position .= ' '
                    . TextUtils::positionBadge((object)['id' => 4, 'short_title' => 'ЦЕН: ' . $player->center_count]);
            }
            if ($player->left_count > 0) {
                $player->position .= ' '
                    . TextUtils::positionBadge((object)['id' => 3, 'short_title' => 'ЛЕВ: ' . $player->left_count]);
            }
            if ($player->right_count > 0) {
                $player->position .= ' '
                    . TextUtils::positionBadge((object)['id' => 5, 'short_title' => 'ПРАВ: ' . $player->right_count]);
            }
            if ($player->defender_count > 0) {
                $player->position .= ' '
                    . TextUtils::positionBadge((object)['id' => 1, 'short_title' => 'ЗАЩ: ' . $player->defender_count]);
            }
            $player->position = trim($player->position);
        }
        unset($player);

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
     *
     * @return array
     */
    private static function _getGoalies(
        array $currentGoalies,
        $currentStats,
        array $previousGoalies = null,
        $previousStats = null
    )
    {
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
        $goaliesAll = [];
        foreach ($currentGoalies as $goalie) {
            $goalie->loses = $goalie->games - $goalie->wins;
            $goalie->saves = $goalie->shot_against - $goalie->goal_against;
            $goalie->saves_percent = round(
                ($goalie->shot_against - $goalie->goal_against) / $goalie->shot_against,
                3
            );
            $goalie->goal_against_per_game = round($goalie->goal_against / $goalie->games, 2);

            if ($goalie->games / $currentGames[$goalie->team_id] >= 0.25) {
                $goalies[] = $goalie;
            }
            $goaliesAll[] = clone $goalie;
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
        array_multisort(
            array_column($goaliesAll, 'tag'),
            SORT_ASC,
            $goaliesAll
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

        $placeAll = 1;
        foreach ($goaliesAll as $goalieAll) {
            $goalieAll->place = $placeAll;
            $goalieAll->prevPlace = '';
            $placeAll += 1;
        }

        return [
            'top' => $goalies,
            'all' => $goaliesAll,
        ];
    }
}
