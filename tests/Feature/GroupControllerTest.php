<?php

namespace Tests\Feature;

use App\Models\GroupGamePlayoff;
use App\Models\GroupGamePlayoffPlayer;
use App\Models\GroupGameRegular;
use App\Models\GroupGameRegularPlayer;
use App\Models\GroupTournament;
use App\Models\GroupTournamentPlayoff;
use App\Models\Player;
use Tests\TestCase;

class GroupControllerTest extends TestCase
{
    protected const PLAYER_ID = 5;

    protected static $tournamentId;

    public function testCreateTournament()
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->putJson(
                '/ajax/group',
                [
                    'platform_id'      => 'xboxone',
                    'app_id'           => 'eanhl19',
                    'title'            => 'Тестовый турнир',
                    'playoff_rounds'   => 2,
                    'min_players'      => 3,
                    'thirdPlaceSeries' => 1,
                    'vk_group_id'      => 115683799,
                    'startedAt'        => '2021-09-12',
                    'playoff_limit'    => 3,
                ]
            );

        $response->assertOk()
            ->assertJsonStructure(['message', 'status', 'data' => ['id']]);
        $responseData = $response->json();

        self::$tournamentId = $responseData['data']['id'];
    }

    /**
     * @depends testCreateTournament
     */
    public function testEditTournament()
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->postJson(
                "/ajax/group/" . self::$tournamentId,
                [
                    'platform_id'      => 'xboxone',
                    'app_id'           => 'eanhl19',
                    'title'            => 'Тестовый турнир (edited)',
                    'playoff_rounds'   => 3,
                    'min_players'      => 6,
                    'thirdPlaceSeries' => 0,
                    'vk_group_id'      => 115683799,
                    'startedAt'        => '2021-09-13',
                    'playoff_limit'    => 6,
                ]
            );

        $response->assertOk()
            ->assertJsonStructure(['message', 'status', 'data' => ['id']]);
    }

    /**
     * @param int $teamId
     * @param int $division
     *
     * @depends      testCreateTournament
     * @dataProvider requiredTeamsProvider
     */
    public function testAddTeam(int $teamId, int $division)
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->putJson(
                "/ajax/group/" . self::$tournamentId . "/team",
                [
                    'team_id'  => $teamId,
                    'division' => $division,
                ]
            );

        $response->assertOk();
    }

    /**
     * @depends testAddTeam
     */
    public function testEditTeam()
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->postJson(
                "/ajax/group/" . self::$tournamentId . "/team/5",
                [
                    'division' => 2,
                ]
            );

        $response->assertOk();
    }

    /**
     * @depends testAddTeam
     */
    public function testDeleteTeam()
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->deleteJson("/ajax/group/" . self::$tournamentId . "/team/8");

        $response->assertOk();
    }

    /**
     * @depends testAddTeam
     */
    public function testAddSchedule()
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->putJson(
                "/ajax/group/" . self::$tournamentId . "/schedule",
                [
                    'gamesCount' => 2,
                    'rounds'     => 1,
                ]
            );

        $response->assertOk();
    }

    /**
     * @depends testAddSchedule
     */
    public function testDeleteTeamWithSchedule()
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->deleteJson("/ajax/group/" . self::$tournamentId . "/team/3");

        $response->assertOk();
        $regularGames = GroupGameRegular::whereTournamentId(self::$tournamentId)
            ->where('home_team_id', '=', 3)
            ->orWhere('away_team_id', '=', 3);
        $this->assertEquals(0, $regularGames->count());
    }

    /**
     * @depends testDeleteTeamWithSchedule
     * @return GroupGameRegular
     */
    public function testEditRegularGame()
    : GroupGameRegular
    {
        $user = Player::find(self::PLAYER_ID);
        $tournament = GroupTournament::find(self::$tournamentId);

        /**
         * @var GroupGameRegular $regularGame
         */
        $regularGame = $tournament->regularGames->first();

        $response = $this->actingAs($user)
            ->postJson(
                "/ajax/group/" . self::$tournamentId . "/regular/$regularGame->id",
                [
                    "game"    => [
                        "home_score"            => "12",
                        "away_score"            => "1",
                        "isOvertime"            => 1,
                        "playedAt"              => "2021-09-12",
                        "home_shot"             => "12",
                        "away_shot"             => "13",
                        "home_hit"              => "13",
                        "away_hit"              => "15",
                        "home_attack_time"      => "03:23",
                        "away_attack_time"      => "04:56",
                        "home_pass_percent"     => 76,
                        "away_pass_percent"     => 67,
                        "home_faceoff"          => "10",
                        "away_faceoff"          => "0",
                        "home_penalty_time"     => "00:00",
                        "away_penalty_time"     => "02:00",
                        "home_penalty_success"  => "1",
                        "home_penalty_total"    => "1",
                        "away_penalty_success"  => "0",
                        "away_penalty_total"    => "0",
                        "home_powerplay_time"   => "01:23",
                        "away_powerplay_time"   => "00:00",
                        "home_shorthanded_goal" => "0",
                        "away_shorthanded_goal" => "1",
                        "isTechnicalDefeat"     => 0,
                    ],
                    "players" => [
                        "home" => [
                            [
                                "team_id"     => $regularGame->home_team_id,
                                "player_id"   => 1,
                                "position_id" => 1,
                                "goals"       => 3,
                                "assists"     => 5,
                                "star"        => 0,
                                "isGoalie"    => 0,
                            ],
                        ],
                        "away" => [
                            [
                                "team_id"     => $regularGame->home_team_id,
                                "player_id"   => 2,
                                "position_id" => 1,
                                "goals"       => 0,
                                "assists"     => 1,
                                "star"        => 0,
                                "isGoalie"    => 0,
                            ],
                        ],
                    ],
                ]
            );

        $response->assertOk();

        return $regularGame;
    }

    /**
     * @param GroupGameRegular $game
     *
     * @depends testEditRegularGame
     */
    public function testGetLastGames(GroupGameRegular $game)
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->getJson("/ajax/ea/lastGames?gameId=$game->id");

        $response->assertOk();
    }

    /**
     * @param GroupGameRegular $game
     *
     * @return GroupGameRegularPlayer
     * @depends testEditRegularGame
     */
    public function testCreateRegularProtocol(GroupGameRegular $game)
    : GroupGameRegularPlayer
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->putJson(
                "/ajax/group/" . self::$tournamentId . "/regular/$game->id/protocol",
                [
                    "game_id"     => $game->id,
                    "team_id"     => $game->home_team_id,
                    "player_id"   => 3,
                    "position_id" => 0,
                    "goals"       => 0,
                    "assists"     => 0,
                    "star"        => 0,
                    "isGoalie"    => 1,
                ]
            );

        $response->assertOk()
            ->assertJsonStructure(['message', 'status', 'data' => ['id']]);
        $responseData = $response->json();

        return GroupGameRegularPlayer::find($responseData['data']['id']);
    }

    /**
     * @param GroupGameRegularPlayer $protocol
     *
     * @return GroupGameRegularPlayer
     * @depends testCreateRegularProtocol
     */
    public function testUpdateRegularProtocol(GroupGameRegularPlayer $protocol)
    : GroupGameRegularPlayer
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->postJson(
                "/ajax/group/" . self::$tournamentId . "/regular/$protocol->game_id/protocol/$protocol->id",
                [
                    "game_id"     => $protocol->game_id,
                    "team_id"     => $protocol->team_id,
                    "player_id"   => 3,
                    "position_id" => 0,
                    "goals"       => 0,
                    "assists"     => 0,
                    "star"        => 1,
                    "isGoalie"    => 1,
                ]
            );

        $response->assertOk();

        return $protocol;
    }

    /**
     * @param GroupGameRegularPlayer $protocol
     *
     * @depends testUpdateRegularProtocol
     */
    public function testDeleteRegularProtocol(GroupGameRegularPlayer $protocol)
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->deleteJson(
                "/ajax/group/" . self::$tournamentId . "/regular/$protocol->game_id/protocol/$protocol->id"
            );

        $response->assertOk();
    }

    /**
     * @param GroupGameRegular $game
     *
     * @depends testEditRegularGame
     */
    public function testConfirmRegularResult(GroupGameRegular $game)
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->postJson("/ajax/group/" . self::$tournamentId . "/regular/$game->id/confirm");

        $response->assertOk();
    }

    /**
     * @param GroupGameRegular $game
     *
     * @depends testEditRegularGame
     */
    public function testResetRegularGame(GroupGameRegular $game)
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->postJson("/ajax/group/" . self::$tournamentId . "/regular/$game->id/reset");

        $response->assertOk();
    }

    /**
     * @return GroupTournamentPlayoff
     * @depends testAddTeam
     */
    public function testCreatePair()
    : GroupTournamentPlayoff
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->putJson(
                "/ajax/group/" . self::$tournamentId . "/playoff",
                [
                    'round'       => 1,
                    'pair'        => 1,
                    'team_one_id' => 1,
                    'team_two_id' => 2,
                ]
            );

        $response->assertOk()
            ->assertJsonStructure(['message', 'status', 'data' => ['id']]);
        $responseData = $response->json();

        return GroupTournamentPlayoff::find($responseData['data']['id']);
    }

    /**
     * @param GroupTournamentPlayoff $pair
     *
     * @return GroupTournamentPlayoff
     * @depends testCreatePair
     */
    public function testUpdatePair(GroupTournamentPlayoff $pair)
    : GroupTournamentPlayoff
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->postJson(
                "/ajax/group/" . self::$tournamentId . "/playoff/$pair->id",
                [
                    'team_one_id' => 2,
                    'team_two_id' => 1,
                ]
            );

        $response->assertOk();

        return $pair;
    }

    /**
     * @param GroupTournamentPlayoff $pair
     *
     * @return GroupGamePlayoff
     * @depends testUpdatePair
     */
    public function testCreatePlayoffGame(GroupTournamentPlayoff $pair)
    : GroupGamePlayoff
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->putJson(
                "/ajax/group/" . self::$tournamentId . "/playoff/$pair->id",
                [
                    "game"    => [
                        "home_score"            => "12",
                        "away_score"            => "1",
                        "isOvertime"            => 1,
                        "playedAt"              => "2021-09-12",
                        "home_shot"             => "12",
                        "away_shot"             => "13",
                        "home_hit"              => "13",
                        "away_hit"              => "15",
                        "home_attack_time"      => "03:23",
                        "away_attack_time"      => "04:56",
                        "home_pass_percent"     => 76,
                        "away_pass_percent"     => 67,
                        "home_faceoff"          => "10",
                        "away_faceoff"          => "0",
                        "home_penalty_time"     => "00:00",
                        "away_penalty_time"     => "02:00",
                        "home_penalty_success"  => "1",
                        "home_penalty_total"    => "1",
                        "away_penalty_success"  => "0",
                        "away_penalty_total"    => "0",
                        "home_powerplay_time"   => "01:23",
                        "away_powerplay_time"   => "00:00",
                        "home_shorthanded_goal" => "0",
                        "away_shorthanded_goal" => "1",
                        "isTechnicalDefeat"     => 0,
                    ],
                    "players" => [
                        "home" => [
                            [
                                "team_id"     => $pair->team_one_id,
                                "player_id"   => 1,
                                "position_id" => 1,
                                "goals"       => 3,
                                "assists"     => 5,
                                "star"        => 0,
                                "isGoalie"    => 0,
                            ],
                        ],
                        "away" => [
                            [
                                "team_id"     => $pair->team_two_id,
                                "player_id"   => 2,
                                "position_id" => 1,
                                "goals"       => 0,
                                "assists"     => 1,
                                "star"        => 0,
                                "isGoalie"    => 0,
                            ],
                        ],
                    ],
                ]
            );

        $response->assertOk()
            ->assertJsonStructure(['message', 'status', 'data' => ['id']]);
        $responseData = $response->json();

        return GroupGamePlayoff::find($responseData['data']['id']);
    }

    /**
     * @param GroupGamePlayoff $game
     *
     * @return GroupGamePlayoff
     * @depends testCreatePlayoffGame
     */
    public function testEditPlayoffGame(GroupGamePlayoff $game)
    : GroupGamePlayoff
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->postJson(
                "/ajax/group/" . self::$tournamentId . "/playoff/$game->playoff_pair_id/$game->id",
                [
                    "game" => [
                        "home_score" => "12",
                        "away_score" => "1",
                        "isOvertime" => 0,
                    ],
                ]
            );

        $response->assertOk();

        return $game;
    }

    /**
     * @param GroupGamePlayoff $game
     *
     * @return GroupGamePlayoffPlayer
     * @depends testEditPlayoffGame
     */
    public function testCreatePlayoffProtocol(GroupGamePlayoff $game)
    : GroupGamePlayoffPlayer
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->putJson(
                "/ajax/group/" . self::$tournamentId . "/playoff/$game->playoff_pair_id/$game->id/protocol",
                [
                    "game_id"     => $game->id,
                    "team_id"     => $game->home_team_id,
                    "player_id"   => 3,
                    "position_id" => 0,
                    "goals"       => 0,
                    "assists"     => 0,
                    "star"        => 0,
                    "isGoalie"    => 1,
                ],
            );

        $response->assertOk()
            ->assertJsonStructure(['message', 'status', 'data' => ['id']]);
        $responseData = $response->json();

        return GroupGamePlayoffPlayer::find($responseData['data']['id']);
    }

    /**
     * @param GroupGamePlayoffPlayer $protocol
     *
     * @return GroupGamePlayoffPlayer
     * @depends testCreatePlayoffProtocol
     */
    public function testUpdatePlayoffProtocol(GroupGamePlayoffPlayer $protocol)
    : GroupGamePlayoffPlayer
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->postJson(
                "/ajax/group/" . self::$tournamentId . "/playoff/{$protocol->playoffGame->playoff_pair_id}/$protocol->game_id/protocol/$protocol->id",
                [
                    "game_id"     => $protocol->game_id,
                    "team_id"     => $protocol->team_id,
                    "player_id"   => 3,
                    "position_id" => 0,
                    "goals"       => 0,
                    "assists"     => 0,
                    "star"        => 1,
                    "isGoalie"    => 1,
                ]
            );

        $response->assertOk();

        return $protocol;
    }

    /**
     * @param GroupGamePlayoffPlayer $protocol
     *
     * @depends testUpdatePlayoffProtocol
     */
    public function testDeletePlayoffProtocol(GroupGamePlayoffPlayer $protocol)
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->deleteJson(
                "/ajax/group/" . self::$tournamentId . "/playoff/{$protocol->playoffGame->playoff_pair_id}/$protocol->game_id/protocol/$protocol->id"
            );

        $response->assertOk();
    }

    /**
     * @param GroupGamePlayoff $game
     *
     * @depends testEditPlayoffGame
     */
    public function testConfirmPlayoffResult(GroupGamePlayoff $game)
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->postJson(
                "/ajax/group/" . self::$tournamentId . "/playoff/$game->playoff_pair_id/$game->id/confirm",
            );

        $response->assertOk();
    }

    /**
     * @param GroupGamePlayoff $game
     *
     * @depends testEditPlayoffGame
     */
    public function testResetPlayoffGame(GroupGamePlayoff $game)
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->postJson(
                "/ajax/group/" . self::$tournamentId . "/playoff/$game->playoff_pair_id/$game->id/reset",
            );

        $response->assertOk();
    }

    /**
     * @param int $teamId
     * @param int $place
     *
     * @depends      testCreateTournament
     * @dataProvider requiredWinnersProvider
     */
    public function testSetWinner(int $teamId, int $place)
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->postJson(
                "/ajax/group/" . self::$tournamentId . "/winner",
                [
                    'team_id' => $teamId,
                    'place'   => $place,
                ]
            );

        $response->assertOk();
    }

    /**
     * @depends testAddSchedule
     */
    public function testDeleteSchedule()
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->deleteJson("/ajax/group/" . self::$tournamentId . "/schedule");

        $response->assertOk();
    }

    /**
     * @depends testCreateTournament
     */
    public function testDeleteTournament()
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->deleteJson("/ajax/group/" . self::$tournamentId);

        $response->assertOk();
    }

    /**
     * @return int[][]
     */
    public function requiredTeamsProvider()
    : array
    {
        return [
            [1, 1],
            [2, 1],
            [3, 1],
            [4, 1],
            [5, 1],
            [6, 2],
            [7, 2],
            [8, 2],
        ];
    }

    /**
     * @return int[][]
     */
    public function requiredWinnersProvider()
    : array
    {
        return [
            [1, 1],
            [2, 2],
        ];
    }
}
