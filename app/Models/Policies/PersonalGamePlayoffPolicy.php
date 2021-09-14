<?php

namespace App\Models\Policies;


use App\Models\PersonalGamePlayoff;
use App\Models\Player;
use Illuminate\Auth\Access\HandlesAuthorization;

class PersonalGamePlayoffPolicy
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
     * @param Player              $user
     * @param PersonalGamePlayoff $personalGamePlayoff
     *
     * @return bool
     */
    public function update(Player $user, PersonalGamePlayoff $personalGamePlayoff)
    : bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->id === $personalGamePlayoff->home_player_id) {
            return true;
        }

        if ($user->id === $personalGamePlayoff->away_player_id) {
            return true;
        }

        return false;
    }
}