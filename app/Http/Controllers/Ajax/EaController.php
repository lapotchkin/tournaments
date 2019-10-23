<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequest;
use App\Models\EaRest;
use App\Models\GroupGameRegular;
use App\Models\GroupTournamentPlayoff;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

/**
 * Class EaController
 * @package App\Http\Controllers\Ajax
 */
class EaController extends Controller
{
    /**
     * @param StoreRequest $request
     * @return ResponseFactory|Response
     * @throws GuzzleException
     * @throws Exception
     */
    public function getLastGames(StoreRequest $request)
    {
        $validatedData = $request->validate([
            'gameId' => 'sometimes|required|int',
            'pairId' => 'sometimes|required|int',
        ]);

        $game = null;
        $pair = null;
        $app = null;
        $clubId = null;
        $platform = null;
        if (isset($validatedData['gameId'])) {
            $game = GroupGameRegular::find($validatedData['gameId']);
            $clubId = $game->homeTeam->team->getClubId($game->tournament->app_id);
            $platform = $game->tournament->platform_id === 'playstation4' ? 'ps4' : $game->tournament->platform_id;
            $app = $game->tournament->app_id;
        } elseif (isset($validatedData['pairId'])) {
            $pair = GroupTournamentPlayoff::find($validatedData['pairId']);
            $clubId = $pair->teamOne->getClubId($pair->tournament->app_id);
            $platform = $pair->tournament->platform_id === 'playstation4' ? 'ps4' : $pair->tournament->platform_id;
            $app = $pair->tournament->app_id;
        } else {
            abort('404', 'Не указан ID для поиска');
        }
        if (is_null($pair) && is_null($game)) {
            abort(400, 'Не найдена пара или игра');
        }

        $responseJSON = EaRest::readGames($platform, $app, $clubId);
        $matches = EaRest::parseMatches(
            json_decode($responseJSON, true),
            $pair ? $pair->tournament : $game->tournament,
            $pair ? $pair->teamOne : $game->homeTeam->team,
            $pair ? $pair->teamTwo : $game->awayTeam->team
        );

        return $this->renderAjax($matches);
    }
}
