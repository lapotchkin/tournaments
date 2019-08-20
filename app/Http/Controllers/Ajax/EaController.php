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
use stdClass;

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
    const OVERTIME_RESULTS = [5, 6];

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
        $awayTeam = Team::find((int)$validatedData['awayTeamId']);
        if (is_null($tournament) || is_null($homeTeam) || is_null($awayTeam)) {
            abort(404);
        }

        $clubId = $homeTeam->getClubId($tournament->app_id);
        $httpClient = new Client(['base_uri' => self::BASE_URL]);
        $response = $httpClient->request(
            'GET',
            str_replace(
                ['{platformId}', '{clubId}'],
                [$homeTeam->platform_id, $clubId],
                self::API_PATH . self::MATCHES_PATH
            ),
            ['query' => ['match_type' => 'club_private', 'matches_returned' => self::MATCHES_PER_REQUEST]]
        );
        $matches = $this->parseMatches(
            json_decode((string)$response->getBody(), true),
            $tournament,
            $homeTeam,
            $awayTeam
        );

        return $this->renderAjax(
            [
                $matches,
                json_decode((string)$response->getBody(), true),
            ]
        );
    }

    protected function parseMatches(array $response, GroupTournament $tournament, Team $homeTeam, Team $awayTeam)
    {
        $matches = [];
        $homeClubId = (int)$homeTeam->getClubId($tournament->app_id);
        $awayClubId = (int)$awayTeam->getClubId($tournament->app_id);
        foreach ($response['raw'] as $matchId => $match) {
            $clubIds = array_keys($match['clubs']);
            if (!in_array($homeClubId, $clubIds) || !in_array($awayClubId, $clubIds)) {
                continue;
            }

            $matches[$matchId] = [
                'game'    => [
                    'home_score'            => 0,
                    'away_score'            => 0,
                    'home_shot'             => 0,
                    'away_shot'             => 0,
                    'home_hit'              => 0,
                    'away_hit'              => 0,
                    'home_attack_time'      => '00:00:00',
                    'away_attack_time'      => '00:00:00',
                    'home_pass_percent'     => '',
                    'away_pass_percent'     => '',
                    'home_faceoff'          => 0,
                    'away_faceoff'          => 0,
                    'home_penalty_time'     => '00:00:00',
                    'away_penalty_time'     => '00:00:00',
                    'home_penalty_total'    => 0,
                    'away_penalty_total'    => 0,
                    'home_penalty_success'  => 0,
                    'away_penalty_success'  => 0,
                    'home_powerplay_time'   => '00:00:00',
                    'away_powerplay_time'   => '00:00:00',
                    'home_shorthanded_goal' => 0,
                    'away_shorthanded_goal' => 0,
                    'isOvertime'            => 0,
                    'match_id'              => $matchId,
                ],
                'players' => [
                    'home' => [],
                    'away' => [],
                ],
            ];

            foreach ($match['clubs'] as $clubId => $club) {
                if ((int)$clubId === $homeClubId) {
                    $matches[$matchId]['game']['isOvertime']
                        = (int)in_array((int)$club['result'], self::OVERTIME_RESULTS);

                    $matches[$matchId]['game']['home_score'] = (int)$club['goals'];
                } else {
                    $matches[$matchId]['game']['away_score'] = (int)$club['goals'];
                }
            }

            foreach ($match['players'] as $clubId => $players) {
                foreach ($players as $player) {
                    if ((int)$clubId === $homeClubId) {
                        $matches[$matchId]['game']['home_shot'] += (int)$player['skshots'];
                        $matches[$matchId]['game']['home_hit'] += (int)$player['skhits'];
                    } else {
                        $matches[$matchId]['game']['away_shot'] += (int)$player['skshots'];
                        $matches[$matchId]['game']['away_hit'] += (int)$player['skhits'];
                    }
                }
            }
        }

        return $matches;
    }
}
