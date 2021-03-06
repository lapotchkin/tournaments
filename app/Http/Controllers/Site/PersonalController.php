<?php

namespace App\Http\Controllers\Site;

use App\Http\Requests\StoreRequest;
use App\Models\App;
use App\Models\Club;
use App\Models\League;
use App\Models\PersonalTournament;
use App\Models\PersonalTournamentPlayer;
use App\Models\Platform;
use App\Models\Player;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PersonalController extends Controller
{
    public function index()
    {
        $apps = App::with(['personalTournaments.platform'])
            ->orderByDesc('createdAt')
            ->get();

        return view('site.personal.index', [
            'apps' => $apps,
        ]);
    }

    /**
     * @param StoreRequest $request
     * @return Factory|View
     */
    public function new(StoreRequest $request)
    {
        return view('site.personal.tournament_editor', [
            'title'      => 'Новый турнир',
            'platforms'  => Platform::all(),
            'apps'       => App::all(),
            'leagues'    => League::all(),
            'tournament' => null,
        ]);
    }

    /**
     * @param StoreRequest $request
     * @param int          $tournamentId
     * @return Factory|View
     */
    public function edit(StoreRequest $request, int $tournamentId)
    {
        /** @var PersonalTournament $tournament */
        $tournament = PersonalTournament::find($tournamentId);
        if (is_null($tournament)) {
            abort(404);
        }

        return view('site.personal.tournament_editor', [
            'title'      => $tournament->title . ': Редактировать турнир',
            'platforms'  => Platform::all(),
            'apps'       => App::all(),
            'leagues'    => League::all(),
            'tournament' => $tournament,
        ]);
    }

    /**
     * @param Request $request
     * @param int     $tournamentId
     * @return Factory|View
     */
    public function players(Request $request, int $tournamentId)
    {
        /** @var PersonalTournament $tournament */
        $tournament = PersonalTournament::with(['tournamentPlayers', 'tournamentPlayers.player', 'winners.player'])
            ->find($tournamentId);
        if (is_null($tournament)) {
            abort(404);
        }

        $divisions = [];
        $playerIds = [];
        foreach ($tournament->tournamentPlayers as $tournamentPlayer) {
            $playerIds[] = $tournamentPlayer->player_id;
            $divisions[$tournamentPlayer->division][] = $tournamentPlayer;
        }
        foreach ($divisions as &$division) {
            usort($division, function ($a, $b) {
                return strcmp(mb_strtolower($a->player->tag), mb_strtolower($b->player->tag));
//                return strcmp(mb_strtolower($a->createdAt), mb_strtolower($b->createdAt));
            });
        }
        unset($division);
        ksort($divisions);

        if (strstr($request->path(), 'copypaste')) {
            return view('site.personal.copypaste', [
                'tournament' => $tournament,
                'divisions'  => $divisions,
            ]);
        }

        $nonTournamentPlayers = Player::whereNotIn('id', $playerIds)
            ->where('platform_id', $tournament->platform_id)
            ->orderBy('tag')
            ->get();

        return view('site.personal.players', [
            'tournament'           => $tournament,
            'divisions'            => $divisions,
            'nonTournamentPlayers' => $nonTournamentPlayers,
        ]);
    }

    /**
     * @param StoreRequest $request
     * @param int          $tournamentId
     * @param int          $playerId
     * @return Factory|View
     */
    public function player(StoreRequest $request, int $tournamentId, int $playerId)
    {
        /** @var PersonalTournamentPlayer $tournamentPlayer */
        $tournamentPlayer = PersonalTournamentPlayer::where('tournament_id', $tournamentId)
            ->where('player_id', $playerId)
            ->first();

        if (is_null($tournamentPlayer)) {
            abort(404);
        }

        $clubs = Club::where('league_id', '=', $tournamentPlayer->tournament->league_id)
            ->orderBy('title')
            ->get();

        return view('site.personal.player', [
            'title'            => $tournamentPlayer->player->tag .
                ($tournamentPlayer->player->name ? ' (' . $tournamentPlayer->player->name . ')' : ''),
            'tournamentPlayer' => $tournamentPlayer,
            'clubs'            => $clubs,
        ]);
    }

    /**
     * @param StoreRequest $request
     * @param int          $tournamentId
     * @return Factory|View
     */
    public function map(StoreRequest $request, int $tournamentId)
    {
        /** @var PersonalTournament $tournament */
        $tournament = PersonalTournament::find($tournamentId);
        if (is_null($tournament)) {
            abort(404);
        }

        $points = [];
        foreach ($tournament->players as $player) {
            $points[] = [$player->lat, $player->lon, $player->name . ' (' . $player->city . ')'];
        }

        return view('site.personal.map', [
            'tournament' => $tournament,
            'points'     => $points,
        ]);
    }
}
