<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Requests\StoreGroupTournament;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequest;
use App\Models\GroupGameRegular;
use App\Models\GroupTournament;
use App\Models\GroupTournamentTeam;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

/**
 * Class GroupController
 * @package App\Http\Controllers\Ajax
 */
class GroupController extends Controller
{
    /**
     * @param StoreGroupTournament $request
     * @return ResponseFactory|Response
     */
    public function create(StoreGroupTournament $request)
    {
        $validatedData = $request->validated();

        $tournament = new GroupTournament;
        $tournament->platform_id = $validatedData['platform_id'];
        $tournament->app_id = $validatedData['app_id'];
        $tournament->title = $validatedData['title'];
        $tournament->playoff_rounds = $validatedData['playoff_rounds'];
        $tournament->min_players = $validatedData['min_players'];

        $tournament->save();

        return $this->renderAjax(['id' => $tournament->id]);
    }

    /**
     * @param StoreGroupTournament $request
     * @param int                  $tournamentId
     * @return ResponseFactory|Response
     */
    public function edit(StoreGroupTournament $request, int $tournamentId)
    {
        $validatedData = $request->validated();

        $tournament = GroupTournament::find($tournamentId);
        $tournament->platform_id = $validatedData['platform_id'];
        $tournament->app_id = $validatedData['app_id'];
        $tournament->title = $validatedData['title'];
        $tournament->playoff_rounds = $validatedData['playoff_rounds'];
        $tournament->min_players = $validatedData['min_players'];

        $tournament->save();

        return $this->renderAjax(['id' => $tournament->id]);
    }

    /**
     * @param StoreRequest $request
     * @param int          $tournamentId
     * @return ResponseFactory|Response
     */
    public function delete(StoreRequest $request, int $tournamentId)
    {
        $tournament = GroupTournament::find($tournamentId);

        $tournament->delete();

        return $this->renderAjax();
    }

    /**
     * @param StoreRequest $request
     * @param int          $tournamentId
     * @return ResponseFactory|Response
     */
    public function addTeam(StoreRequest $request, int $tournamentId)
    {
        $validatedData = $request->validate([
            'team_id'  => 'required|int|exists:team,id',
            'division' => 'required|int|min:1|max:26',
        ]);

        $tournamentTeam = GroupTournamentTeam::withTrashed()
            ->where('tournament_id', $tournamentId)
            ->where('team_id', $validatedData['team_id'])
            ->first();

        if (is_null($tournamentTeam)) {
            $tournamentTeam = new GroupTournamentTeam;
            $tournamentTeam->tournament_id = $tournamentId;
            $tournamentTeam->team_id = $validatedData['team_id'];
            $tournamentTeam->division = $validatedData['division'];
            $tournamentTeam->save();
        } else {
            GroupTournamentTeam::withTrashed()
                ->where('tournament_id', $tournamentId)
                ->where('team_id', $validatedData['team_id'])
                ->update([
                    'division'  => $validatedData['division'],
                    'deletedAt' => null,
                ]);
        }


        return $this->renderAjax();
    }

    /**
     * @param StoreRequest $request
     * @param int          $tournamentId
     * @param int          $teamId
     * @return ResponseFactory|Response
     */
    public function editTeam(StoreRequest $request, int $tournamentId, int $teamId)
    {
        $validatedData = $request->validate([
            'division' => 'required|int|min:1|max:26',
        ]);

        GroupTournamentTeam::where('tournament_id', $tournamentId)
            ->where('team_id', $teamId)
            ->update(['division' => $validatedData['division']]);

        return $this->renderAjax();
    }

    /**
     * @param StoreRequest $request
     * @param int          $tournamentId
     * @param int          $teamId
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function deleteTeam(StoreRequest $request, int $tournamentId, int $teamId)
    {
        GroupTournamentTeam::where('tournament_id', $tournamentId)
            ->where('team_id', $teamId)
            ->delete();

        return $this->renderAjax();
    }

    public function editRegularGame(StoreRequest $request, int $tournamentId, int $gameId)
    {
        /** @var GroupGameRegular $game */
        $game = GroupGameRegular::with(['protocols.player', 'homeTeam.team', 'awayTeam.team'])
            ->find($gameId);
        if (is_null($game) || $game->tournament_id !== $tournamentId) {
            abort(404);
        }

        $validatedData = $request->validate([
            'home_score'            => '10',
            'away_score'            => '2',
            'playedAt'              => '2019-08-14',
            'home_shot'             => '16',
            'away_shot'             => '5',
            'home_hit'              => '3',
            'away_hit'              => '1',
            'home_attack_time'      => '10:23',
            'away_attack_time'      => '01:12',
            'home_pass_percent'     => '76.1',
            'away_pass_percent'     => '88.2',
            'home_faceoff'          => '10',
            'away_faceoff'          => '5',
            'home_penalty_time'     => '00:00',
            'away_penalty_time'     => '06:00',
            'home_penalty_success'  => '2',
            'home_penalty_total'    => '3',
            'away_penalty_success'  => '0',
            'away_penalty_total'    => '0',
            'home_powerplay_time'   => '02:45',
            'away_powerplay_time'   => '00:00',
            'home_shorthanded_goal' => '0',
            'away_shorthanded_goal' => '1',
        ]);

        return $this->renderAjax();
    }
}
