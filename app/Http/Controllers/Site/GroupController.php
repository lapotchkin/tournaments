<?php

namespace App\Http\Controllers\Site;

use App\Models\App;
use App\Models\GroupTournament;
use App\Models\GroupTournamentTeam;
use App\Models\Platform;
use App\Models\Team;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

/**
 * Class GroupController
 * @package App\Http\Controllers\Site
 */
class GroupController extends Controller
{
    /**
     * @return Factory|View
     */
    public function index()
    {
        $apps = App::with(['groupTournaments.platform'])
            ->orderByDesc('createdAt')
            ->get();

        return view('site.group.index', [
            'apps' => $apps,
        ]);
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    public function create(Request $request)
    {
        return view('site.group.tournament_editor', [
            'title'      => 'Новый турнир',
            'platforms'  => Platform::all(),
            'apps'       => App::all(),
            'tournament' => null,
        ]);
    }

    /**
     * @param Request         $request
     * @param GroupTournament $groupTournament
     * @return Factory|View
     */
    public function edit(Request $request, GroupTournament $groupTournament)
    {
        return view('site.group.tournament_editor', [
            'title'      => $groupTournament->title . ': Редактировать турнир',
            'platforms'  => Platform::all(),
            'apps'       => App::all(),
            'tournament' => $groupTournament,
        ]);
    }

    /**
     * @param Request         $request
     * @param GroupTournament $groupTournament
     * @return Factory|View
     */
    public function teams(Request $request, GroupTournament $groupTournament)
    {
        $groupTournament->load(['tournamentTeams', 'tournamentTeams.team', 'winners.team']);
        $divisions = [];
        $teamIds = [];
        foreach ($groupTournament->tournamentTeams as $tournamentTeam) {
            $teamIds[] = $tournamentTeam->team_id;
            $divisions[$tournamentTeam->division][] = $tournamentTeam->team;
        }
        foreach ($divisions as &$division) {
            usort($division, function ($a, $b) {
                return strcmp(mb_strtolower($a->name), mb_strtolower($b->name));
            });
        }
        unset($division);
        ksort($divisions);

        if (strstr($request->path(), 'copypaste')) {
            return view('site.group.copypaste', [
                'tournament' => $groupTournament,
                'divisions'  => $divisions,
            ]);
        }

        $nonTournamentTeams = Team::whereNotIn('id', $teamIds)
            ->where('platform_id', $groupTournament->platform_id)
            ->get();

        return view('site.group.teams', [
            'tournament'         => $groupTournament,
            'divisions'          => $divisions,
            'nonTournamentTeams' => $nonTournamentTeams,
        ]);
    }

    /**
     * @param Request         $request
     * @param GroupTournament $groupTournament
     * @param Team            $team
     * @return Factory|View
     */
    public function team(Request $request, GroupTournament $groupTournament, Team $team)
    {
        /** @var GroupTournamentTeam $tournamentTeam */
        $tournamentTeam = GroupTournamentTeam::where('tournament_id', $groupTournament->id)
            ->where('team_id', $team->id)
            ->first();

        if (is_null($tournamentTeam)) {
            abort(404);
        }

        return view('site.group.team', [
            'title'          => $tournamentTeam->team->name,
            'tournamentTeam' => $tournamentTeam,
        ]);
    }
}
