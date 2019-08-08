<?php

namespace App\Http\Controllers\Site;

use App\Models\GroupTournament;
use App\Models\GroupTournamentGoalies;
use App\Models\GroupTournamentLeaders;
use App\Models\GroupTournamentPosition;
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
        $tournament = GroupTournament::find($tournamentId);
        if (is_null($tournament)) {
            abort(404);
        }

        $position = self::_getPosition(GroupTournamentPosition::readPosition($tournamentId));
        $leaders = GroupTournamentLeaders::readLeaders($tournamentId);
        $goalies = self::_getGoalies(
            GroupTournamentGoalies::readGoalies($tournamentId),
            $position
        );

        return view('site.group.regular.index', [
            'tournament' => $tournament,
            'position'   => $position,
            'leaders'    => $leaders,
            'goalies'    => $goalies,
        ]);
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception
     */
    private static function _getPosition(array $data)
    {
        $position = [];
        $pc = count($data);
        for ($i = 0; $i < $pc; $i += 1) {
            $team = $data[$i];
            $goalsDif = $team->goals - $team->goals_against;
            $attackSec = $team->games > 0 ? round($team->attack_time / $team->games) : 0;
            $attackTime = new DateTime();
            $attackTime->setTime(0, 0, 0, 0);
            $attackTime->add(new DateInterval('PT' . $attackSec . 'S'));

            $position[] = [
                'place'                  => $i + 1,
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
     * @param array $data
     * @param array $teamsStats
     * @return array
     */
    private static function _getGoalies(array $data, $teamsStats)
    {
        $games = [];
        foreach ($teamsStats as $stat) {
            $games[$stat['id']] = $stat['games'];
        }

        $goalies = [];
        foreach ($data as $goalie) {
            if ($goalie->games / $games[$goalie->team_id] <= 0.25) {
                continue;
            }

            $goalies[] = [
                'goalie'                => $goalie->goalie,
                'team'                  => $goalie->team,
                'games'                 => $goalie->games,
                'wins'                  => $goalie->wins,
                'loses'                 => $goalie->games - $goalie->wins,
                'shot_against'          => $goalie->shot_against,
                'saves'                 => $goalie->shot_against - $goalie->goal_against,
                'goal_against'          => $goalie->goal_against,
                'saves_percent'         => round(
                    ($goalie->shot_against - $goalie->goal_against) / $goalie->shot_against,
                    3
                ),
                'goal_against_per_game' => round($goalie->goal_against / $goalie->games, 2),
                'shootouts'             => $goalie->shootouts,
            ];
        }

        return $goalies;
    }
}
