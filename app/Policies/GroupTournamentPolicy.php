<?php

namespace App\Policies;

use App\Models\Player;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupTournamentPolicy
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
}
