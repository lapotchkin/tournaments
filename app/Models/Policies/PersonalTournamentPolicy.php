<?php

namespace App\Models\Policies;

use App\Models\Player;
use Illuminate\Auth\Access\HandlesAuthorization;

class PersonalTournamentPolicy
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
     *
     * @return bool
     */
    public function create(Player $user)
    : bool
    {
        return $user->isAdmin();
    }
}