<?php

namespace App\Http\Controllers\Ajax;

use App\Models\GroupGameRegular;
use App\Models\GroupTournament;
use App\Models\Player;
use App\Models\Team;
use DateTime;
use Exception;
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
    const MATCHES_PER_REQUEST = 20;
    const OVERTIME_RESULTS = [5, 6];

    /**
     * @param Request $request
     * @return ResponseFactory|Response
     * @throws GuzzleException
     * @throws Exception
     */
    public function getLastGames(Request $request, int $gameId)
    {
        $game = GroupGameRegular::find($gameId);

        $clubId = $game->homeTeam->team->getClubId($game->tournament->app_id);
        $httpClient = new Client(['base_uri' => self::BASE_URL]);
        $response = $httpClient->request(
            'GET',
            str_replace(
                ['{platformId}', '{clubId}'],
                [
                    $game->tournament->platform_id === 'playstation4' ? 'ps4' : $game->tournament->platform_id,
                    $clubId,
                ],
                self::API_PATH . self::MATCHES_PATH
            ),
            ['query' => ['match_type' => 'club_private', 'matches_returned' => self::MATCHES_PER_REQUEST]]
        );
        $matches = $this->parseMatches(
            json_decode((string)$response->getBody(), true),
            $game->tournament,
            $game->homeTeam->team,
            $game->awayTeam->team
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
                    'home_team'             => null,
                    'away_team'             => null,
                    'home_club_id'          => null,
                    'away_club_id'          => null,
                    'date'                  => $match['timestamp'],
                    'home_score'            => 0,//a
                    'away_score'            => 0,//a
                    'home_shot'             => 0,//a
                    'away_shot'             => 0,//a
                    'home_hit'              => 0,//a
                    'away_hit'              => 0,//a
                    'home_attack_time'      => '',//m — заполняется вручную
                    'away_attack_time'      => '',//m — заполняется вручную
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
                    'home_powerplay_time'   => '00:00',//a
                    'away_powerplay_time'   => '00:00',//a
                    'home_shorthanded_goal' => 0,//a
                    'away_shorthanded_goal' => 0,//a
                    'isOvertime'            => 0,//m — заполняется вручную
                    'isShootout'            => 0,//m — заполняется вручную
                    'isTechnicalDefeat'     => 0,//m — заполняется вручную
                    'match_id'              => $matchId,
                ],
                'players' => [
                    'home' => [],
                    'away' => [],
                ],
            ];

            $date = new DateTime();
            foreach ($match['clubs'] as $clubId => $club) {
                //if ((int)$clubId === $homeClubId) {
                if ((int)$club['teamSide'] === 1) {
                    $matches[$matchId]['game']['isOvertime']
                        = (int)in_array((int)$club['result'], self::OVERTIME_RESULTS);

                    $matches[$matchId]['game']['home_team'] = (int)$clubId === $homeClubId
                        ? $homeTeam->name : $awayTeam->name;
                    $matches[$matchId]['game']['home_club_id'] = (int)$clubId;
                    $matches[$matchId]['game']['home_score'] = (int)$club['goals'];
                    $matches[$matchId]['game']['home_penalty_total'] = (int)$club['ppo'];
                    $matches[$matchId]['game']['home_penalty_success'] = (int)$club['ppg'];
                    $date->setTimestamp((int)$club['toa']);
                    $matches[$matchId]['game']['home_powerplay_time'] = $date->format('i:s');
                } else {
                    $matches[$matchId]['game']['away_team'] = (int)$clubId === $homeClubId
                        ? $homeTeam->name : $awayTeam->name;
                    $matches[$matchId]['game']['away_club_id'] = (int)$clubId;
                    $matches[$matchId]['game']['away_score'] = (int)$club['goals'];
                    $matches[$matchId]['game']['away_penalty_total'] = (int)$club['ppo'];
                    $matches[$matchId]['game']['away_penalty_success'] = (int)$club['ppg'];
                    $date->setTimestamp((int)$club['toa']);
                    $matches[$matchId]['game']['away_powerplay_time'] = $date->format('i:s');
                }
            }

            $homePenaltyTime = 0;
            $awayPenaltyTime = 0;
            foreach ($match['players'] as $clubId => $players) {
                foreach ($players as $player) {
                    if ((int)$clubId === $matches[$matchId]['game']['home_club_id']) {
                        $matches[$matchId]['game']['home_shot'] += (int)$player['skshots'];
                        $matches[$matchId]['game']['home_hit'] += (int)$player['skhits'];
                        $matches[$matchId]['game']['home_faceoff'] += (int)$player['skfow'];
                        $matches[$matchId]['game']['home_shorthanded_goal'] += (int)$player['skshg'];
                        $homePenaltyTime += (int)$player['skpim'];
                        $matches[$matchId]['players']['home'][] = $this->getPlayer(
                            $player,
                            $homeTeam,
                            $matches[$matchId]['game']['home_score'] > $matches[$matchId]['game']['away_score']
                        );
                    } else {
                        $matches[$matchId]['game']['away_shot'] += (int)$player['skshots'];
                        $matches[$matchId]['game']['away_hit'] += (int)$player['skhits'];
                        $matches[$matchId]['game']['away_faceoff'] += (int)$player['skfow'];
                        $matches[$matchId]['game']['away_shorthanded_goal'] += (int)$player['skshg'];
                        $awayPenaltyTime += (int)$player['skpim'];
                        $matches[$matchId]['players']['away'][] = $this->getPlayer(
                            $player,
                            $awayTeam,
                            $matches[$matchId]['game']['home_score'] < $matches[$matchId]['game']['away_score']
                        );
                    }
                }
            }
            $date->setTimestamp($homePenaltyTime * 60);
            $matches[$matchId]['game']['home_penalty_time'] = $date->format('i:s');
            $date->setTimestamp($awayPenaltyTime * 60);
            $matches[$matchId]['game']['away_penalty_time'] = $date->format('i:s');
        }

        return $matches;
    }

    /**
     * @param array $playerData
     * @param int   $teamId
     * @param bool  $isWin
     * @return array
     */
    protected function getPlayer(array $playerData, Team $team, bool $isWin)
    {
        $player = Player::where('tag', '=', $playerData['playername'])
            ->where('platform_id', '=', $team->platform_id)
            ->first();
        $protocol = [
            'name'                => $player->tag,
            'team_id'             => $team->id,
            'player_id'           => $player->id,
            'class_id'            => (int)$playerData['class'],
            'position_id'         => (int)$playerData['position'],
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
            'isGoalie'            => (int)((int)$playerData['position'] === 0),
        ];

        return $protocol;
    }
}
