<?php

namespace App\Policies;

use App\Models\Player;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamManagementPolicy
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
}
