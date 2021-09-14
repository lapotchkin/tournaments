<?php

namespace App\Models\Policies;

use App\Models\PersonalGameRegular;
use App\Models\Player;
use Illuminate\Auth\Access\HandlesAuthorization;

class PersonalGameRegularPolicy
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
     * @param PersonalGameRegular $personalGameRegular
     *
     * @return bool
     */
    public function update(Player $user, PersonalGameRegular $personalGameRegular)
    : bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->id === $personalGameRegular->home_player_id) {
            return true;
        }

        if ($user->id === $personalGameRegular->away_player_id) {
            return true;
        }

        return false;
    }
}