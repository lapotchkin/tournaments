<?php

namespace Tests\Feature;

use App\Models\Player;
use Tests\TestCase;

class PlayerControllerTest extends TestCase
{
    protected const PLAYER_ID = 5;

    public function testCreate()
    : Player
    {
        $user = Player::find(5);
        $response = $this->actingAs($user)
            ->putJson(
                '/ajax/player',
                [
                    'tag'         => 'test_' . rand(10000, 99999),
                    'name'        => 'Анатлий Брусенцов',
                    'platform_id' => 'xboxone',
                ]
            );

        $response->assertOk()
            ->assertJsonStructure(['message', 'status', 'data' => ['id']]);
        $responseData = $response->json();

        return Player::find($responseData['data']['id']);
    }

    /**
     * @param Player $player
     *
     * @return Player
     * @depends testCreate
     */
    public function testEdit(Player $player)
    : Player
    {
        $user = Player::find(5);
        $response = $this->actingAs($user)
            ->postJson(
                "/ajax/player/$player->id",
                [
                    'tag'         => $player->tag,
                    'name'        => 'Анатолий Брусенцов',
                    'vk'          => 'id' . rand(10000, 99999),
                    'city'        => 'Калуга',
                    'lat'         => 54.513845,
                    'lon'         => 36.261224,
                    'platform_id' => $player->platform_id,
                ]
            );

        $response->assertOk();

        return $player;
    }

    /**
     * @param Player $player
     *
     * @depends testCreate
     */
    public function testDelete(Player $player)
    {
        $user = Player::find(5);
        $response = $this->actingAs($user)
            ->deleteJson("/ajax/player/$player->id");

        $response->assertOk();
    }
}
