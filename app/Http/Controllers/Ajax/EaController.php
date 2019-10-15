<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequest;
use App\Models\GroupGameRegular;
use App\Models\GroupTournament;
use App\Models\GroupTournamentPlayoff;
use App\Models\Player;
use App\Models\PlayerPosition;
use App\Models\Team;
use DateInterval;
use DateTime;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Cache;

/**
 * Class EaController
 * @package App\Http\Controllers\Ajax
 */
class EaController extends Controller
{
    const BASE_URL = [
        'eanhl19' => 'https://www.easports.com',
        'eanhl20' => 'https://proclubs.ea.com',
    ];
    const API_PATH = [
        'eanhl19' => 'iframe/nhl14proclubs/api/platforms/{platformId}/clubs/{clubId}/',
        'eanhl20' => 'api/nhl/clubs/',
    ];
    const MATCHES_PATH = 'matches';
    const MATCHES_PER_REQUEST = 20;
    const OVERTIME_RESULTS = [5, 6];
    const MATCH_DEFAULTS = [
        'game'    => [
            'home_team'             => null,
            'away_team'             => null,
            'home_club_id'          => null,
            'away_club_id'          => null,
            'playedAt'              => null,
            'home_score'            => 0,//a
            'away_score'            => 0,//a
            'home_shot'             => 0,//a
            'away_shot'             => 0,//a
            'home_hit'              => 0,//a
            'away_hit'              => 0,//a
            'home_attack_time'      => '00:00',//a
            'away_attack_time'      => '00:00',//a
            'home_pass_percent'     => '',//m — заполняется вручную
            'away_pass_percent'     => '',//m — заполняется вручную
            'home_faceoff'          => 0,//a
            'away_faceoff'          => 0,//a
            'home_penalty_time'     => '00:00',//a
            'away_penalty_time'     => '00:00',//a
            'home_penalty_total'    => 0,//a
            'away_penalty_total'    => 0,//a
            'home_penalty_success'  => 0,//a
            'away_penalty_success'  => 0,//a
            'home_powerplay_time'   => '',//m — заполняется вручную
            'away_powerplay_time'   => '',//m — заполняется вручную
            'home_shorthanded_goal' => 0,//a
            'away_shorthanded_goal' => 0,//a
            'isOvertime'            => 0,//a
            'isShootout'            => 0,//m — заполняется вручную
            'isTechnicalDefeat'     => 0,//m — заполняется вручную
            'match_id'              => null,
        ],
        'players' => [
            'home' => [],
            'away' => [],
        ],
    ];

    /**
     * @param StoreRequest $request
     * @return ResponseFactory|Response
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
        $key = null;
        if (isset($validatedData['gameId'])) {
            $game = GroupGameRegular::find($validatedData['gameId']);
            $key = "game_{$game->id}_response";
            $clubId = $game->homeTeam->team->getClubId($game->tournament->app_id);
            $platform = $game->tournament->platform_id === 'playstation4' ? 'ps4' : $game->tournament->platform_id;
            $app = $game->tournament->app_id;
        } elseif (isset($validatedData['pairId'])) {
            $pair = GroupTournamentPlayoff::find($validatedData['pairId']);
            $key = "pair_{$pair->id}_response";
            $clubId = $pair->teamOne->getClubId($pair->tournament->app_id);
            $platform = $pair->tournament->platform_id === 'playstation4' ? 'ps4' : $pair->tournament->platform_id;
            $app = $pair->tournament->app_id;
        } else {
            abort('404', 'Не указан ID для поиска');
        }
        if (is_null($pair) && is_null($game)) {
            abort(400, 'Не найдена пара или игра');
        }

        //Cache::flush();
        $responseJSON = Cache::remember(
            $key,
            new DateInterval('PT1H'),
            function () use ($clubId, $platform, $app) {
                $httpClient = new Client(['base_uri' => self::BASE_URL[$app]]);
                $response = $httpClient->request(
                    'GET',
                    str_replace(
                        ['{platformId}', '{clubId}'],
                        [$platform, $clubId],
                        self::API_PATH[$app] . self::MATCHES_PATH
                    ),
                    [
                        'headers' => [
                            'Pragma'          => 'no-cache',
                            'Accept'          => 'application/json',
                            'Origin'          => 'https://www.ea.com',
                            'Cache-Control'   => 'no-cache',
                            'Accept-Language' => 'en-us',
                            'Host'            => 'proclubs.ea.com',
                            'User-Agent'      => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.2 Safari/605.1.15',
                            'Referer'         => 'https://www.ea.com/ru-ru/games/nhl/nhl-20/pro-clubs/match-history?clubId=48893&platform=ps4',
                            'Accept-Encoding' => 'gzip, deflate, br',
                            'Connection'      => 'keep-alive',
                        ],
                        'query'   => [
                            'matchType'        => 'club_private',
                            'match_type'       => 'club_private',
                            'matches_returned' => self::MATCHES_PER_REQUEST,
                            'platform'         => $platform,
                            'clubIds'          => $clubId,
                        ],
                    ]
                );

                return (string)$response->getBody();
            }
        );

        $matches = EaController::parseMatches(
            json_decode($responseJSON, true),
            $pair ? $pair->tournament : $game->tournament,
            $pair ? $pair->teamOne : $game->homeTeam->team,
            $pair ? $pair->teamTwo : $game->awayTeam->team
        );

        return $this->renderAjax($matches);
    }

    /**
     * @param array           $response
     * @param GroupTournament $tournament
     * @param Team            $homeTeam
     * @param Team            $awayTeam
     * @return array
     * @throws Exception
     */
    protected static function parseMatches(array $response, GroupTournament $tournament, Team $homeTeam, Team $awayTeam)
    {
        $matches = [];
        $homeClubId = (int)$homeTeam->getClubId($tournament->app_id);
        $awayClubId = (int)$awayTeam->getClubId($tournament->app_id);
        $positions = self::getPlayerPositions();

        $data = isset($response['raw']) ? $response['raw'] : $response;
        foreach ($data as $match) {
            $matchId = $match['matchId'];
            $clubIds = array_keys($match['clubs']);
            if (!in_array($homeClubId, $clubIds) || !in_array($awayClubId, $clubIds)) {
                continue;
            }

            //Проверка — существует ли матч в системе
            //$matchInSystem = GroupGameRegular::where('match_id', '=', $matchId)->exists();
            //if ($matchInSystem) {
            //    continue;
            //}

            $date = new DateTime();
            $date->setTimestamp($match['timestamp']);
            $matches[$matchId] = self::MATCH_DEFAULTS;
            $matches[$matchId]['game']['playedAt'] = $date->format('Y-m-d');
            $matches[$matchId]['game']['match_id'] = $matchId;

            $date = new DateTime();
            foreach ($match['clubs'] as $clubId => $club) {
                if ((int)$clubId === $homeClubId) {
                    //if ((int)$club['teamSide'] === 1) {
                    $matches[$matchId]['game']['isOvertime']
                        = (int)in_array((int)$club['result'], self::OVERTIME_RESULTS);

                    $matches[$matchId]['game']['home_team'] = (int)$clubId === $homeClubId
                        ? $homeTeam->name : $awayTeam->name;
                    $matches[$matchId]['game']['home_club_id'] = (int)$clubId;
                    $matches[$matchId]['game']['home_score'] = (int)$club['goals'];
                    $matches[$matchId]['game']['home_penalty_total'] = (int)$club['ppo'];
                    $matches[$matchId]['game']['home_penalty_success'] = (int)$club['ppg'];
                    $date->setTimestamp((int)$club['toa']);
                    $matches[$matchId]['game']['home_attack_time'] = $date->format('i:s');
                    $matches[$matchId]['game']['away_shot'] = (int)$match['aggregate'][$clubId]['glshots'];
                    $matches[$matchId]['game']['home_hit'] = (int)$match['aggregate'][$clubId]['skhits'];
                    $matches[$matchId]['game']['home_faceoff'] = (int)$match['aggregate'][$clubId]['skfow'];
                    $matches[$matchId]['game']['home_shorthanded_goal'] = (int)$match['aggregate'][$clubId]['skshg'];
                    $date->setTimestamp((int)$match['aggregate'][$clubId]['skpim'] * 60);
                    $matches[$matchId]['game']['home_penalty_time'] = $date->format('i:s');
                } else {
                    $matches[$matchId]['game']['away_team'] = (int)$clubId === $homeClubId
                        ? $homeTeam->name : $awayTeam->name;
                    $matches[$matchId]['game']['away_club_id'] = (int)$clubId;
                    $matches[$matchId]['game']['away_score'] = (int)$club['goals'];
                    $matches[$matchId]['game']['away_penalty_total'] = (int)$club['ppo'];
                    $matches[$matchId]['game']['away_penalty_success'] = (int)$club['ppg'];
                    $date->setTimestamp((int)$club['toa']);
                    $matches[$matchId]['game']['away_attack_time'] = $date->format('i:s');
                    $matches[$matchId]['game']['home_shot'] = (int)$match['aggregate'][$clubId]['glshots'];
                    $matches[$matchId]['game']['away_hit'] = (int)$match['aggregate'][$clubId]['skhits'];
                    $matches[$matchId]['game']['away_faceoff'] = (int)$match['aggregate'][$clubId]['skfow'];
                    $matches[$matchId]['game']['away_shorthanded_goal'] = (int)$match['aggregate'][$clubId]['skshg'];
                    $date->setTimestamp((int)$match['aggregate'][$clubId]['skpim'] * 60);
                    $matches[$matchId]['game']['away_penalty_time'] = $date->format('i:s');
                }
            }

            foreach ($match['players'] as $clubId => $players) {
                foreach ($players as $player) {
                    if ((int)$clubId === $matches[$matchId]['game']['home_club_id']) {
                        $matches[$matchId]['players']['home'][] = self::getPlayer(
                            $player,
                            $homeTeam,
                            $matches[$matchId]['game']['home_score'] > $matches[$matchId]['game']['away_score'],
                            $positions
                        );
                    } else {
                        $matches[$matchId]['players']['away'][] = self::getPlayer(
                            $player,
                            $awayTeam,
                            $matches[$matchId]['game']['home_score'] < $matches[$matchId]['game']['away_score'],
                            $positions
                        );
                    }
                }
            }
        }

        return $matches;
    }

    /**
     * @param array $playerData
     * @param Team  $team
     * @param bool  $isWin
     * @param array $positions
     * @return array
     * @throws Exception
     */
    protected static function getPlayer(array $playerData, Team $team, bool $isWin, array $positions)
    {
        //echo $playerData['playername'] . PHP_EOL;
        $player = Player::where('tag', '=', $playerData['playername'])
            ->where('platform_id', '=', $team->platform_id)
            ->first();

        if (is_null($player)) {
            throw new Exception("Player {$playerData['playername']} is not in the DB");
        }

        $positionId = is_numeric($playerData['position'])
            ? (int)$playerData['position']
            : $positions['byEaId'][$playerData['position']]->id;
        $protocol = [
            'name'                => $player->tag,
            'team_id'             => $team->id,
            'player_id'           => $player->id,
            'class_id'            => (int)$playerData['class'],
            'position_id'         => $positionId,
            'position'            => is_numeric($playerData['position'])
                ? $positions['byId'][(int)$playerData['position']]
                : $positions['byEaId'][$playerData['position']],
            'star'                => 0,//m — Заполняется вручную
            'time_on_ice_seconds' => (int)$playerData['toiseconds'],
            'goals'               => (int)$playerData['skgoals'],
            'power_play_goals'    => (int)$playerData['skppg'],
            'shorthanded_goals'   => (int)$playerData['skshg'],
            'game_winning_goals'  => (int)$playerData['skgwg'],
            'assists'             => (int)$playerData['skassists'],
            'shots'               => (int)$playerData['skshots'],
            'plus_minus'          => (int)$playerData['skplusmin'],
            'faceoff_win'         => (int)$playerData['skfow'],
            'faceoff_lose'        => (int)$playerData['skfol'],
            'blocks'              => (int)$playerData['skbs'],
            'giveaways'           => (int)$playerData['skgiveaways'],
            'takeaways'           => (int)$playerData['sktakeaways'],
            'hits'                => (int)$playerData['skhits'],
            'penalty_minutes'     => (int)$playerData['skpim'],
            'rating_defense'      => (float)$playerData['ratingDefense'],
            'rating_offense'      => (float)$playerData['ratingOffense'],
            'rating_teamplay'     => (float)$playerData['ratingTeamplay'],
            'shots_on_goal'       => (int)$playerData['glshots'],
            'saves'               => (int)$playerData['glsaves'],
            'breakeaway_shots'    => (int)$playerData['glbrkshots'],
            'breakeaway_saves'    => (int)$playerData['glbrksaves'],
            'penalty_shots'       => (int)$playerData['glpenshots'],
            'penalty_saves'       => (int)$playerData['glpensaves'],
            'goals_against'       => (int)$playerData['glga'],
            'pokechecks'          => (int)$playerData['glpokechecks'],
            'isWin'               => (int)$isWin,
            'isGoalie'            => (int)($positionId === 0),
        ];

        return $protocol;
    }

    /**
     * @return array
     */
    protected static function getPlayerPositions()
    {
        $playerPositions = PlayerPosition::all();
        $result = [
            'byId'   => [],
            'byEaId' => [],
        ];
        foreach ($playerPositions as $position) {
            $result['byId'][$position->id] = (object)[
                'id'          => $position->id,
                'title'       => $position->title,
                'short_title' => $position->short_title,
                'ea_id'       => $position->ea_id,
            ];
            $result['byEaId'][$position->ea_id] = (object)[
                'id'          => $position->id,
                'title'       => $position->title,
                'short_title' => $position->short_title,
                'ea_id'       => $position->ea_id,
            ];
        }
        return $result;
    }
}
