<?php

namespace App\Models\Policies;

use App\Models\PersonalTournamentPlayoff;
use App\Models\Player;
use Illuminate\Auth\Access\HandlesAuthorization;

class PersonalTournamentPlayoffPolicy
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
     * @param Player                    $user
     * @param PersonalTournamentPlayoff $personalTournamentPlayoff
     *
     * @return bool
     */
    public function update(Player $user, PersonalTournamentPlayoff $personalTournamentPlayoff)
    : bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->id === $personalTournamentPlayoff->player_one_id) {
            return true;
        }

        if ($user->id === $personalTournamentPlayoff->player_two_id) {
            return true;
        }

        return false;
    }
}