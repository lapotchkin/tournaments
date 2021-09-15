<?php

namespace App\Http\Controllers\Site;

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
     * @param Request $request
     *
     * @return Factory|View
     */
    public function create(Request $request)
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
     * @param Request            $request
     * @param PersonalTournament $personalTournament
     *
     * @return Factory|View
     */
    public function edit(Request $request, PersonalTournament $personalTournament)
    {
        return view('site.personal.tournament_editor', [
            'title'      => $personalTournament->title . ': Редактировать турнир',
            'platforms'  => Platform::all(),
            'apps'       => App::all(),
            'leagues'    => League::all(),
            'tournament' => $personalTournament,
        ]);
    }

    /**
     * @param Request            $request
     * @param PersonalTournament $personalTournament
     *
     * @return Factory|View
     */
    public function players(Request $request, PersonalTournament $personalTournament)
    {
        $divisions = [];
        $playerIds = [];
        foreach ($personalTournament->tournamentPlayers as $tournamentPlayer) {
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
                'tournament' => $personalTournament,
                'divisions'  => $divisions,
            ]);
        }

        $nonTournamentPlayers = Player::whereNotIn('id', $playerIds)
            ->where('platform_id', $personalTournament->platform_id)
            ->orderBy('tag')
            ->get();

        return view('site.personal.players', [
            'tournament'           => $personalTournament,
            'divisions'            => $divisions,
            'nonTournamentPlayers' => $nonTournamentPlayers,
        ]);
    }

    /**
     * @param Request            $request
     * @param PersonalTournament $personalTournament
     * @param Player             $player
     *
     * @return Factory|View
     */
    public function player(
        Request            $request,
        PersonalTournament $personalTournament,
        Player             $player
    )
    {
        /** @var PersonalTournamentPlayer $tournamentPlayer */
        $tournamentPlayer = PersonalTournamentPlayer::where('tournament_id', $personalTournament->id)
            ->where('player_id', $player->id)
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
     * @param Request            $request
     * @param PersonalTournament $personalTournament
     *
     * @return Factory|View
     */
    public function map(Request $request, PersonalTournament $personalTournament)
    {
        $points = [];
        foreach ($personalTournament->players as $player) {
            $points[] = [$player->lat, $player->lon, $player->name . ' (' . $player->city . ')'];
        }

        return view('site.personal.map', [
            'tournament' => $personalTournament,
            'points'     => $points,
        ]);
    }
}
