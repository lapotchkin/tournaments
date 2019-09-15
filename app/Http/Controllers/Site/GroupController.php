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
use Illuminate\Support\Facades\Auth;
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
     * @return Factory|View
     */
    public function new()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403);
        }

        return view('site.group.tournament_editor', [
            'title'      => 'Новый турнир',
            'platforms'  => Platform::all(),
            'apps'       => App::all(),
            'tournament' => null,
        ]);
    }

    /**
     * @param Request $request
     * @param int     $tournamentId
     * @return Factory|View
     */
    public function edit(Request $request, int $tournamentId)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403);
        }

        /** @var GroupTournament $tournament */
        $tournament = GroupTournament::find($tournamentId);
        if (is_null($tournament)) {
            abort(404);
        }

        return view('site.group.tournament_editor', [
            'title'      => $tournament->title . ': Редактировать турнир',
            'platforms'  => Platform::all(),
            'apps'       => App::all(),
            'tournament' => $tournament,
        ]);
    }

    /**
     * @param Request $request
     * @param int     $tournamentId
     * @return Factory|View
     */
    public function teams(Request $request, int $tournamentId)
    {
        /** @var GroupTournament $tournament */
        $tournament = GroupTournament::with(['tournamentTeams', 'tournamentTeams.team', 'winners.team'])
            ->find($tournamentId);
        if (is_null($tournament)) {
            abort(404);
        }

        $divisions = [];
        $teamIds = [];
        foreach ($tournament->tournamentTeams as $tournamentTeam) {
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
                'tournament' => $tournament,
                'divisions'  => $divisions,
            ]);
        }

        $nonTournamentTeams = Team::whereNotIn('id', $teamIds)
            ->where('platform_id', $tournament->platform_id)
            ->get();

        return view('site.group.teams', [
            'tournament'         => $tournament,
            'divisions'          => $divisions,
            'nonTournamentTeams' => $nonTournamentTeams,
        ]);
    }

    /**
     * @param Request $request
     * @param int     $tournamentId
     * @param int     $teamId
     * @return Factory|View
     */
    public function team(Request $request, int $tournamentId, int $teamId)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403);
        }

        /** @var GroupTournamentTeam $tournamentTeam */
        $tournamentTeam = GroupTournamentTeam::where('tournament_id', $tournamentId)
            ->where('team_id', $teamId)
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
