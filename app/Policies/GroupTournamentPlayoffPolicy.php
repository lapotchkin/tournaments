<?php

namespace App\Policies;

use App\Models\GroupTournamentPlayoff;
use App\Models\Player;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupTournamentPlayoffPolicy
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
     * @param Player                 $user
     * @param GroupTournamentPlayoff $groupTournamentPlayoff
     * @return bool
     */
    public function update(Player $user, GroupTournamentPlayoff $groupTournamentPlayoff)
    {
        if ($user->isAdmin()) {
            return true;
        }
        foreach ($groupTournamentPlayoff->teamOne->teamPlayers as $teamPlayer) {
            if ($user->id === $teamPlayer->player_id && $teamPlayer->isCaptain > 0) {
                return true;
            }
        }
        foreach ($groupTournamentPlayoff->teamTwo->teamPlayers as $teamPlayer) {
            if ($user->id === $teamPlayer->player_id && $teamPlayer->isCaptain > 0) {
                return true;
            }
        }
        return false;
    }
}
