<?php

namespace App\Models\Policies;

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
     * @param Player $user
     * @return bool
     */
    public function create(Player $user)
    {
        return $user->isAdmin();
    }

    /**
     * @param Player $user
     * @param Team   $team
     * @return bool
     */
    public function update(Player $user, Team $team)
    {
        if ($user->isAdmin()) {
            return true;
        }
        foreach ($team->teamPlayers as $teamPlayer) {
            if ($user->id === $teamPlayer->player_id && $teamPlayer->isCaptain === 1) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param Player $user
     * @param Team   $team
     * @return bool
     */
    public function manage(Player $user, Team $team)
    {
        if ($user->isAdmin()) {
            return true;
        }
        foreach ($team->teamPlayers as $teamPlayer) {
            if ($user->id === $teamPlayer->player_id && $teamPlayer->isCaptain > 0) {
                return true;
            }
        }
        return false;
    }
}
