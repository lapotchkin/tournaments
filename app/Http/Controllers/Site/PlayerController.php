<?php

namespace App\Http\Controllers\Site;

use App\Models\PersonalTournament;
use App\Models\Platform;
use App\Models\Player;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

/**
 * Class PlayerController
 * @package App\Http\Controllers\Site
 */
class PlayerController extends Controller
{
    /**
     * @return Factory|View
     */
    public function index()
    {
        $winners = [];
        $tournaments = PersonalTournament::with(['winners.player'])->get();
        foreach ($tournaments as $tournament) {
            foreach ($tournament->winners as $winner) {
                if (!isset($winners[$winner->player->id])) {
                    $winners[$winner->player->id] = (object)[
                        'player' => $winner->player,
                        'cups'   => [
                            1 => 0,
                            2 => 0,
                            3 => 0,
                        ],
                    ];
                }
                $winners[$winner->player->id]->cups[$winner->place] += 1;
            }
        }
        usort($winners, function ($a, $b) {
            if ($a->cups[1] === $b->cups[1] && $a->cups[2] === $b->cups[2] && $a->cups[3] === $b->cups[3]) {
                return 0;
            } elseif ($a->cups[1] > $b->cups[1]) {
                return -1;
            } elseif ($a->cups[1] === $b->cups[1] && $a->cups[2] > $b->cups[2]) {
                return -1;
            } elseif ($a->cups[1] === $b->cups[1] && $a->cups[2] === $b->cups[2] && $a->cups[3] > $b->cups[3]) {
                return -1;
            }
            return 1;
        });

        $platforms = Platform::all();
        $players = [];
        foreach ($platforms as $platform) {
            $players[$platform->id] = (object)[
                'platform' => $platform,
                'players'  => Player::wherePlatformId($platform->id)->orderBy('tag')->get(),
            ];
        }

        return view('site.player.index', [
            'players' => $players,
            'winners' => $winners,
        ]);
    }

    public function player(Request $request, int $playerId)
    {
        $player = Player::with(['teamPlayers.team', 'personalTournamentPlayers.tournament.winners'])->find($playerId);

        return view('site.player.player', [
            'player' => $player,
        ]);
    }
}
