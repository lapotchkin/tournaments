<?php

namespace App\Policies;

use App\Models\Player;
use App\Models\Team;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class TeamPolicy
 * @package App\Policies
 */
class TeamPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param Player $player
     * @return bool
     */
    public function create(Player $player)
    {
        return $player->isAdmin();
    }

    /**
     * @param Player $player
     * @param Team   $team
     * @return bool
     */
    public function update(Player $player, Team $team)
    {
        if ($player->isAdmin()) {
            return true;
        }
        foreach ($team->teamPlayers as $teamPlayer) {
            if ($teamPlayer->isCaptain > 0) {
                return true;
            }
        }
        return false;
    }
}
