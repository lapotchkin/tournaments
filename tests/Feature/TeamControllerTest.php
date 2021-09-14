<?php

namespace Tests\Feature;

use App\Models\Player;
use App\Models\Team;
use Tests\TestCase;

class TeamControllerTest extends TestCase
{
    protected const PLAYER_ID = 5;

    protected static $teamId;

    public function testCreate()
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->putJson(
                "/ajax/team",
                [
                    'name'        => 'Урюпинские бобры ' . rand(10000, 99999),
                    'short_name'  => 'UBO',
                    'platform_id' => 'xboxone',
                ]
            );

        $response->assertOk()
            ->assertJsonStructure(['message', 'status', 'data' => ['id']]);
        $responseData = $response->json();
        self::$teamId = $responseData['data']['id'];
    }

    /**
     * @depends testCreate
     */
    public function testEdit()
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->postJson(
                "/ajax/team/" . self::$teamId,
                [
                    'name'        => 'Урюпинские выдры ' . rand(10000, 99999),
                    'short_name'  => 'UO',
                    'platform_id' => 'xboxone',
                ]
            );

        $response->assertOk();
    }

    /**
     * @depends testCreate
     */
    public function testSetTeamId()
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->postJson(
                "/ajax/team/" . self::$teamId . "/app",
                [
                    'app_id'      => 'eanhl19',
                    'app_team_id' => 123,
                ]
            );

        $response->assertOk();
    }

    /**
     * @depends testSetTeamId
     */
    public function testDeleteTeamId()
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->deleteJson("/ajax/team/" . self::$teamId . "/app/eanhl19");

        $response->assertOk();
    }

    /**
     * @depends testCreate
     */
    public function testAddPlayer()
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->putJson(
                "/ajax/team/" . self::$teamId,
                [
                    'player_id' => 1,
                ]
            );

        $response->assertOk();
    }

    /**
     * @depends testAddPlayer
     */
    public function testUpdatePlayer()
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->postJson(
                "/ajax/team/" . self::$teamId . "/1",
                [
                    'isCaptain' => 1,
                ]
            );

        $response->assertOk();
    }

    /**
     * @depends testAddPlayer
     */
    public function testDeletePlayer()
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->deleteJson("/ajax/team/" . self::$teamId . "/1");

        $response->assertOk();
    }

    /**
     * @depends testCreate
     */
    public function testDelete()
    {
        $user = Player::find(self::PLAYER_ID);
        $response = $this->actingAs($user)
            ->deleteJson("/ajax/team/" . self::$teamId);

        $response->assertOk();
    }
}
