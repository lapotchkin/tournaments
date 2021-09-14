<?php

namespace App\Models\Policies;

use App\Models\GroupGameRegular;
use App\Models\Player;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupGameRegularPolicy
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
     * @param GroupGameRegular $groupGameRegular
     *
     * @return bool
     */
    public function update(Player $user, GroupGameRegular $groupGameRegular)
    : bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        foreach ($groupGameRegular->homeTeam->team->teamPlayers as $teamPlayer) {
            if ($user->id === $teamPlayer->player_id && $teamPlayer->isCaptain > 0) {
                return true;
            }
        }

        foreach ($groupGameRegular->awayTeam->team->teamPlayers as $teamPlayer) {
            if ($user->id === $teamPlayer->player_id && $teamPlayer->isCaptain > 0) {
                return true;
            }
        }

        return false;
    }
}
