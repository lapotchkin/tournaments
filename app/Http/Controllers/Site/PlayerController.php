<?php

namespace App\Http\Controllers\Site;

use App\Http\Requests\StoreRequest;
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
        usort($winners, 'self::sortWinners');

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

    /**
     * @param Request $request
     * @param Player  $player
     * @return Factory|View
     */
    public function player(Request $request, Player $player)
    {
        $player->load(['teams', 'tournaments.winners']);
        return view('site.player.player', [
            'player' => $player,
        ]);
    }

    /**
     * @param StoreRequest $request
     * @return Factory|View
     */
    public function add(StoreRequest $request)
    {
        return view('site.player.player_form', [
            'title'     => 'Добавить игрока',
            'player'    => null,
            'platforms' => Platform::all(),
        ]);
    }

    /**
     * @param StoreRequest $request
     * @param Player       $player
     * @return Factory|View
     */
    public function edit(StoreRequest $request, Player $player)
    {
        return view('site.player.player_form', [
            'title'     => 'Изменить даные игрока',
            'player'    => $player,
            'platforms' => Platform::all(),
        ]);
    }
}
