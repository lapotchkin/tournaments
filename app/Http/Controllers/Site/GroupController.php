<?php

namespace App\Http\Controllers\Site;

use App\Models\App;
use App\Models\GroupTournament;
use App\Models\GroupTournamentTeam;
use App\Models\Platform;
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
        $apps = App::all();

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
        $tournament = GroupTournament::find($tournamentId);
        $groupTournamentTeams = $tournament->groupTournamentTeams;
        $divisions = [];
        foreach ($groupTournamentTeams as $groupTournamentTeam) {
            $divisions[$groupTournamentTeam->division][] = $groupTournamentTeam->team;
        }
        foreach ($divisions as &$division) {
            usort($division, function ($a, $b) {
                return strcmp(mb_strtolower($a->name), mb_strtolower($b->name));
            });
        }
        unset($division);

        return view('site.group.teams', [
            'tournament' => $tournament,
            'divisions'  => $divisions,
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

        /** @var GroupTournamentTeam $groupTournamentTeam */
        $groupTournamentTeam = GroupTournamentTeam::where('tournament_id', $tournamentId)
            ->where('team_id', $teamId)
            ->first();

        return view('site.group.team', [
            'title'               => $groupTournamentTeam->team->name,
            'groupTournamentTeam' => $groupTournamentTeam,
        ]);
    }
}
