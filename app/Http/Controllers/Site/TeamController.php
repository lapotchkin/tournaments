<?php

namespace App\Http\Controllers\Site;

use App\Models\App;
use App\Models\GroupTournament;
use App\Models\Platform;
use App\Models\Player;
use App\Models\PlayerStats;
use App\Models\Team;
use App\Models\TeamPlayer;
use App\Models\TeamStats;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use stdClass;

/**
 * Class TeamController
 *
 * @package App\Http\Controllers\Site
 */
class TeamController extends Controller
{
    /**
     * @return Factory|View
     */
    public function index()
    {
        $winners = [];
        $tournaments = GroupTournament::with(['winners.team'])->get();
        foreach ($tournaments as $tournament) {
            foreach ($tournament->winners as $winner) {
                if (!isset($winners[$winner->team->id])) {
                    $winners[$winner->team->id] = (object)[
                        'team' => $winner->team,
                        'cups' => [1 => 0, 2 => 0, 3 => 0],
                    ];
                }
                $winners[$winner->team->id]->cups[$winner->place] += 1;
            }
        }
        usort($winners, 'self::sortWinners');

        $platforms = Platform::all();
        $teams = [];
        foreach ($platforms as $platform) {
            $teams[$platform->id] = (object)[
                'platform' => $platform,
                'teams'    => Team::wherePlatformId($platform->id)->orderBy('name')->get(),
            ];
        }

        return view('site.team.index', [
            'teams'   => $teams,
            'winners' => $winners,
        ]);
    }

    /**
     * @param Request $request
     * @param Team    $team
     *
     * @return Factory|View
     */
    public function team(Request $request, Team $team)
    {
        //$team = Team::with(['teamPlayers', 'tournaments'])->find($teamId);
        $teamPlayers = $team->teamPlayers->filter(function (TeamPlayer $teamPlayer) {
            return !is_null($teamPlayer->player);
        });

        $playerIds = $teamPlayers->map(function (TeamPlayer $teamPlayer, $key) {
            return $teamPlayer->player_id;
        });

        $teamPlayers = $teamPlayers->sortBy(function (TeamPlayer $teamPlayer, $key) {
            return mb_strtolower($teamPlayer->player->tag);
        });
        $nonTeamPlayers = Player::whereNotIn('id', $playerIds)
            ->where('platform_id', $team->platform_id)
            ->orderBy('tag')
            ->get();
        $statsData = TeamStats::readStats($team->id);
        $stats = self::getStats($statsData[0]);
        $scoreDynamics = TeamStats::readScoreDynamics($team->id);
        $i = 1;
        foreach ($scoreDynamics as $stat) {
            $stat->index = $i;
            $i += 1;
        }

        $players = PlayerStats::readTeamPlayersStats($team->id);
        $goalies = PlayerStats::readTeamGoaliesStats($team->id);

        return view('site.team.team', [
            'team'           => $team,
            'teamPlayers'    => $teamPlayers,
            'nonTeamPlayers' => $nonTeamPlayers,
            'stats'          => $stats,
            'scoreDynamics'  => $scoreDynamics,
            'players'        => $players,
            'goalies'        => $goalies,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Factory|View
     */
    public function add(Request $request)
    {
        return view('site.team.team_form', [
            'title'     => 'Добавить команду',
            'team'      => null,
            'platforms' => Platform::all(),
            'apps'      => null,
        ]);
    }

    /**
     * @param Request $request
     * @param int     $teamId
     *
     * @return Factory|View
     */
    public function edit(Request $request, int $teamId)
    {
        $team = Team::find($teamId);
        if (is_null($team)) {
            abort(404);
        }

        return view('site.team.team_form', [
            'title'     => 'Изменить даные команды',
            'team'      => $team,
            'platforms' => Platform::all(),
            'apps'      => App::all(),
        ]);
    }

    /**
     * @param stdClass $statsData
     *
     * @return array
     */
    private static function getStats(stdClass $statsData)
    {
        $stats = [
            'games'          => (int)$statsData->games,
            'gamesResults'   => [
                ['category' => 'Победы', 'value' => (int)$statsData->wins, 'color' => '#519E1E'],
                ['category' => 'Победы в ОТ', 'value' => (int)$statsData->wins_ot, 'color' => '#A0CA84'],
                ['category' => 'Поражения', 'value' => (int)$statsData->lose, 'color' => '#FF5016'],
                ['category' => 'Поражения в ОТ', 'value' => (int)$statsData->lose_ot, 'color' => '#FFB600'],
            ],
            'faceoff'        => [
                ['category' => 'Выиграно', 'value' => (int)$statsData->faceoff, 'color' => '#519E1E'],
                ['category' => 'Проиграно', 'value' => 100 - (int)$statsData->faceoff, 'color' => '#FF5016'],
            ],
            'penaltyFor'     => [
                ['category' => 'Реализовано', 'value' => (int)$statsData->penalty_for_success, 'color' => '#519E1E'],
                ['category' => 'Не реализовано', 'value' => (int)$statsData->penalty_for - (int)$statsData->penalty_for_success, 'color' => '#FF5016'],
            ],
            'penaltyAgainst' => [
                ['category' => 'Нейтрализовано', 'value' => (int)$statsData->penalty_against - (int)$statsData->penalty_against_success, 'color' => '#519E1E'],
                ['category' => 'Не нейтрализовано', 'value' => (int)$statsData->penalty_against_success, 'color' => '#FF5016'],
            ],
        ];

        return $stats;
    }
}
