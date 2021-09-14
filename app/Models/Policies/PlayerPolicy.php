<?php

namespace App\Models\Policies;

use App\Models\Player;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlayerPolicy
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

    /**
     * @param Player $user
     * @param Player $player
     *
     * @return bool
     */
    public function update(Player $user, Player $player)
    : bool
    {
        return $user->isAdmin() || $user->id === $player->id;
    }
}
