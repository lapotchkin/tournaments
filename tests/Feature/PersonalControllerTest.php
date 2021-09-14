<?php

namespace Tests\Feature;

use App\Models\PersonalGamePlayoff;
use App\Models\PersonalGameRegular;
use App\Models\PersonalTournament;
use App\Models\PersonalTournamentPlayoff;
use App\Models\Player;
use Tests\TestCase;

class PersonalControllerTest extends TestCase
{
    protected const PLAYER_ID = 5;

    protected static $tournamentId;

    public function testCreate()
    {
        $user = Player::find(5);
        $response = $this->actingAs($user)
            ->putJson(
                "/ajax/personal",
                [
                    'platform_id'      => 'xboxone',
                    'app_id'           => 'eanhl19',
                    'league_id'        => 'nhl',
                    'title'            => 'Турнир 1 на 1',
                    'playoff_rounds'   => 2,
                    'thirdPlaceSeries' => 1,
                    'vk_group_id'      => 115683799,
                    'startedAt'        => '2021-09-13',
                    'playoff_limit'    => 2,
                ]
            );

        $response->assertOk()
            ->assertJsonStructure(['message', 'status', 'data' => ['id']]);
        $responseData = $response->json();

        self::$tournamentId = $responseData['data']['id'];
    }

    /**
     * @depends testCreate
     */
    public function testEdit()
    {
        $user = Player::find(5);
        $response = $this->actingAs($user)
            ->postJson(
                "/ajax/personal/" . self::$tournamentId,
                [
                    'platform_id'      => 'xboxone',
                    'app_id'           => 'eanhl19',
                    'league_id'        => 'nhl',
                    'title'            => 'Турнир 1 на 1 (edited)',
                    'playoff_rounds'   => 3,
                    'thirdPlaceSeries' => 0,
                    'vk_group_id'      => 115683799,
                    'startedAt'        => '2021-09-12',
                    'playoff_limit'    => 3,
                ]
            );

        $response->assertOk();
    }

    /**
     * @depends      testCreate
     * @dataProvider requiredPlayersProvider
     */
    public function testAddPlayer(int $userId, int $division, string $clubId)
    {
        $user = Player::find(5);
        $response = $this->actingAs($user)
            ->putJson(
                "/ajax/personal/" . self::$tournamentId . "/player",
                [
                    'player_id' => $userId,
                    'division'  => $division,
                    'club_id'   => $clubId,
                ]
            );

        $response->assertOk();
    }

    /**
     * @depends testAddPlayer
     */
    public function testEditPlayer()
    {
        $user = Player::find(5);
        $response = $this->actingAs($user)
            ->postJson(
                "/ajax/personal/" . self::$tournamentId . "/player/5",
                [
                    'division' => 2,
                ]
            );

        $response->assertOk();
    }

    /**
     * @depends testAddPlayer
     */
    public function testDeletePlayer()
    {
        $user = Player::find(5);
        $response = $this->actingAs($user)
            ->deleteJson("/ajax/personal/" . self::$tournamentId . "/player/8");

        $response->assertOk();
    }

    /**
     * @depends testDeletePlayer
     */
    public function testAddSchedule()
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->putJson(
                "/ajax/personal/" . self::$tournamentId . "/schedule",
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
    public function testDeletePlayerWithSchedule()
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->deleteJson("/ajax/personal/" . self::$tournamentId . "/player/9");

        $response->assertOk();
        $regularGames = PersonalGameRegular::whereTournamentId(self::$tournamentId)
            ->where('home_player_id', '=', 9)
            ->orWhere('away_player_id', '=', 9);
        $this->assertEquals(0, $regularGames->count());
    }

    /**
     * @depends testDeletePlayerWithSchedule
     */
    public function testEditRegularGame()
    {
        $user = Player::find(self::PLAYER_ID);
        $tournament = PersonalTournament::find(self::$tournamentId);

        /**
         * @var PersonalGameRegular $regularGame
         */
        $regularGame = $tournament->regularGames->first();

        $response = $this->actingAs($user)
            ->postJson(
                "/ajax/personal/" . self::$tournamentId . "/regular/$regularGame->id",
                [
                    'home_score'        => 2,
                    'away_score'        => 3,
                    'isOvertime'        => 0,
                    'isShootout'        => 1,
                    'isTechnicalDefeat' => 0,
                    'playedAt'          => '2021-09-14',
                ]
            );

        $response->assertOk();
    }

    /**
     * @return PersonalTournamentPlayoff
     * @depends testDeletePlayerWithSchedule
     */
    public function testCreatePair()
    : PersonalTournamentPlayoff
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->putJson(
                "/ajax/personal/" . self::$tournamentId . "/playoff",
                [
                    'round'         => 1,
                    'pair'          => 1,
                    'player_one_id' => 1,
                    'player_two_id' => 2,
                ]
            );

        $response->assertOk()
            ->assertJsonStructure(['message', 'status', 'data' => ['id']]);
        $responseData = $response->json();

        return PersonalTournamentPlayoff::find($responseData['data']['id']);
    }

    /**
     * @param PersonalTournamentPlayoff $pair
     *
     * @return PersonalTournamentPlayoff
     * @depends testCreatePair
     */
    public function testUpdatePair(PersonalTournamentPlayoff $pair)
    : PersonalTournamentPlayoff
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->postJson(
                "/ajax/personal/" . self::$tournamentId . "/playoff/$pair->id",
                [
                    'player_one_id' => 2,
                    'player_two_id' => 1,
                ]
            );

        $response->assertOk();

        return $pair;
    }

    /**
     * @param PersonalTournamentPlayoff $pair
     *
     * @return PersonalGamePlayoff
     * @depends testUpdatePair
     */
    public function testCreatePlayoffGame(PersonalTournamentPlayoff $pair)
    : PersonalGamePlayoff
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->putJson(
                "/ajax/personal/" . self::$tournamentId . "/playoff/$pair->id",
                [
                    'home_score'        => "2",
                    'away_score'        => "3",
                    'isOvertime'        => 0,
                    'isShootout'        => 1,
                    'isTechnicalDefeat' => 0,
                    'playedAt'          => '2021-09-14',
                ]
            );

        $response->assertOk()
            ->assertJsonStructure(['message', 'status', 'data' => ['id']]);
        $responseData = $response->json();

        return PersonalGamePlayoff::find($responseData['data']['id']);
    }

    /**
     * @param PersonalGamePlayoff $game
     *
     * @depends testCreatePlayoffGame
     */
    public function testEditPlayoffGame(PersonalGamePlayoff $game)
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->postJson(
                "/ajax/personal/" . self::$tournamentId . "/playoff/$game->playoff_pair_id/$game->id",
                [
                    "game" => [
                        "home_score" => "4",
                        "away_score" => "5",
                        "isOvertime" => 1,
                        'isShootout' => 0,
                    ],
                ]
            );

        $response->assertOk();
    }

    /**
     * @depends      testDeletePlayerWithSchedule
     * @dataProvider requiredWinnersProvider
     */
    public function testSetWinner(int $userId, int $place)
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->postJson(
                "/ajax/personal/" . self::$tournamentId . "/winner",
                [
                    'player_id' => $userId,
                    'place'     => $place,
                ]
            );

        $response->assertOk();
    }

    /**
     * @depends testSetWinner
     */
    public function testDeleteSchedule()
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->deleteJson("/ajax/personal/" . self::$tournamentId . "/schedule");

        $response->assertOk();
    }

    /**
     * @depends testDeleteSchedule
     */
    public function testDelete()
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->deleteJson("/ajax/personal/" . self::$tournamentId);

        $response->assertOk();
    }

    /**
     * @return int[][]
     */
    public function requiredPlayersProvider()
    : array
    {
        return [
            [1, 1, 'ana'],
            [2, 1, 'ari'],
            [3, 1, 'aut'],
            [4, 1, 'bel'],
            [5, 1, 'bos'],
            [6, 2, 'buf'],
            [7, 2, 'can'],
            [8, 2, 'car'],
            [9, 2, 'cbj'],
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
