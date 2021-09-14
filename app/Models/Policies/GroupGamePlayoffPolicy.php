<?php

namespace App\Models\Policies;

use App\Models\GroupGamePlayoff;
use App\Models\Player;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupGamePlayoffPolicy
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
     * @param Player           $user
     * @param GroupGamePlayoff $groupGamePlayoff
     *
     * @return bool
     */
    public function update(Player $user, GroupGamePlayoff $groupGamePlayoff)
    : bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        foreach ($groupGamePlayoff->homeTeam->team->teamPlayers as $teamPlayer) {
            if ($user->id === $teamPlayer->player_id && $teamPlayer->isCaptain > 0) {
                return true;
            }
        }

        foreach ($groupGamePlayoff->awayTeam->team->teamPlayers as $teamPlayer) {
            if ($user->id === $teamPlayer->player_id && $teamPlayer->isCaptain > 0) {
                return true;
            }
        }

        return false;
    }
}
