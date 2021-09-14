<?php

namespace App\Providers;

use App\Models\GroupGamePlayoff;
use App\Models\GroupGameRegular;
use App\Models\GroupTournament;
use App\Models\GroupTournamentPlayoff;
use App\Models\Player;
use App\Models\Team;
use App\Models\Policies\GroupGamePlayoffPolicy;
use App\Models\Policies\GroupGameRegularPolicy;
use App\Models\Policies\GroupTournamentPlayoffPolicy;
use App\Models\Policies\GroupTournamentPolicy;
use App\Models\Policies\PlayerPolicy;
use App\Models\Policies\TeamPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
