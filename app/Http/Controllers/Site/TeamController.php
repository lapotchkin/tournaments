<?php

namespace App\Http\Controllers\Site;

use App\Models\GroupTournament;
use App\Models\Platform;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class TeamController
 * @package App\Http\Controllers\Site
 */
class TeamController extends Controller
{
    public function index()
    {
        $winners = [];
        $tournaments = GroupTournament::with(['winners.team'])->get();
        foreach ($tournaments as $tournament) {
            foreach ($tournament->winners as $winner) {
                if (!isset($winners[$winner->team->id])) {
                    $winners[$winner->team->id] = (object)[
                        'team' => $winner->team,
                        'cups' => [
                            1 => 0,
                            2 => 0,
                            3 => 0,
                        ],
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

    public function team(Request $request, int $teamId)
    {
        $team = Team::with(['players', 'tournaments'])->find($teamId);

        $playerIds = [];
        foreach ($team->teamPlayers as $teamPlayer) {
            $playerIds[] = $teamPlayer->player_id;
        }
        $nonTeamPlayers = Player::whereNotIn('id', $playerIds)
            ->where('platform_id', $team->platform_id)
            ->orderBy('tag')
            ->get();

        return view('site.team.team', [
            'team'           => $team,
            'nonTeamPlayers' => $nonTeamPlayers,
        ]);
    }

    public function add()
    {

    }

    public function edit()
    {

    }
}
