<?php

namespace App\Http\Controllers\Ajax;

use App\Models\GroupTournament;
use App\Models\Team;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

/**
 * Class EaController
 * @package App\Http\Controllers\Ajax
 */
class EaController extends Controller
{
    const BASE_URL = 'https://www.easports.com';
    const API_PATH = 'iframe/nhl14proclubs/api/platforms/{platformId}/clubs/{clubId}/';
    const MATCHES_PATH = 'matches';
    const MATCHES_PER_REQUEST = '10';

    /**
     * @param Request $request
     * @return ResponseFactory|Response
     * @throws GuzzleException
     */
    public function getLastGames(Request $request)
    {
        $validatedData = $request->validate([
            'tournamentId' => 'required|int|min:1',
            'homeTeamId'   => 'required|int|min:1',
            'awayTeamId'   => 'required|int|min:1',
        ]);

        $tournament = GroupTournament::find((int)$validatedData['tournamentId']);
        $homeTeam = Team::find((int)$validatedData['homeTeamId']);
        if (is_null($homeTeam)) {
            abort(400, 'Нет такой команды');
        }

        $clubId = $homeTeam->appTeams
            ->where('app_id', '=', $tournament->app_id)
            ->where('team_id', '=', $homeTeam->id)
            ->first()
            ->app_team_id;
        $httpClient = new Client(['base_uri' => self::BASE_URL]);
        $response = $httpClient->request(
            'GET',
            str_replace(
                ['{platformId}', '{clubId}'],
                [$homeTeam->platform_id, $clubId],
                self::API_PATH . self::MATCHES_PATH
            ),
            [
                'query' => [
                    //'filters'          => 'sum,pretty',
                    'match_type'       => 'club_private',
                    'matches_returned' => self::MATCHES_PER_REQUEST,
                ],
            ]
        );

        return $this->renderAjax(json_decode((string)$response->getBody(), true));
    }
}
