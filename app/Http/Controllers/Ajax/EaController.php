<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\EaGame;
use App\Models\EaRest;
use App\Models\GroupGameRegular;
use App\Models\GroupTournamentPlayoff;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class EaController
 *
 * @package App\Http\Controllers\Ajax
 */
class EaController extends Controller
{
    /**
     * @param Request $request
     *
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function getLastGames(Request $request)
    {
        $validatedData = $request->validate([
            'gameId' => 'sometimes|required|int',
            'pairId' => 'sometimes|required|int',
        ]);

        $game = null;
        $pair = null;
        $firstClubId = null;
        $secondClubId = null;
        $tournament = null;
        if (isset($validatedData['gameId'])) {
            $game = GroupGameRegular::find($validatedData['gameId']);
            $firstClubId = $game->homeTeam->team->getClubId($game->tournament->app_id);
            $secondClubId = $game->awayTeam->team->getClubId($game->tournament->app_id);
            $tournament = $game->tournament;
        } elseif (isset($validatedData['pairId'])) {
            $pair = GroupTournamentPlayoff::find($validatedData['pairId']);
            $firstClubId = $pair->teamOne->getClubId($pair->tournament->app_id);
            $secondClubId = $pair->teamTwo->getClubId($pair->tournament->app_id);
            $tournament = $pair->tournament;
        } else {
            abort('404', 'Не указан ID для поиска');
        }
        if (is_null($pair) && is_null($game)) {
            abort(400, 'Не найдена пара или игра');
        }

        $response = EaGame::where('clubs.' . $firstClubId, 'exists', true)
            ->where('clubs.' . $secondClubId, 'exists', true)
            ->where('timestamp', '>', $tournament->startedAt->getTimestamp())
            ->orderBy('timestamp')
            ->get();

        $matches = EaRest::parseMatches(
            $response,
            $pair ? $pair->tournament : $game->tournament,
            $pair ? $pair->teamOne : $game->homeTeam->team,
            $pair ? $pair->teamTwo : $game->awayTeam->team
        );

        return $this->renderAjax($matches);
    }
}
