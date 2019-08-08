<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Requests\StoreGroupTournament;
use App\Http\Controllers\Controller;
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
     * @param Request $request
     * @param int     $tournamentId
     * @return ResponseFactory|Response
     */
    public function delete(Request $request, int $tournamentId)
    {
        $tournament = GroupTournament::find($tournamentId);

        $tournament->delete();

        return $this->renderAjax();
    }

    /**
     * @param Request $request
     * @param int     $tournamentId
     * @return ResponseFactory|Response
     */
    public function addTeam(Request $request, int $tournamentId)
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
     * @param Request $request
     * @param int     $tournamentId
     * @param int     $teamId
     * @return ResponseFactory|Response
     */
    public function editTeam(Request $request, int $tournamentId, int $teamId)
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
     * @param Request $request
     * @param int     $tournamentId
     * @param int     $teamId
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function deleteTeam(Request $request, int $tournamentId, int $teamId)
    {
        GroupTournamentTeam::where('tournament_id', $tournamentId)
            ->where('team_id', $teamId)
            ->delete();

        return $this->renderAjax();
    }
}
