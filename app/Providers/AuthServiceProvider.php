<?php

namespace App\Providers;

use App\Models\GroupGamePlayoff;
use App\Models\GroupGameRegular;
use App\Models\GroupTournament;
use App\Models\GroupTournamentPlayoff;
use App\Models\Player;
use App\Models\Team;
use App\Policies\GroupGamePlayoffPolicy;
use App\Policies\GroupGameRegularPolicy;
use App\Policies\GroupTournamentPlayoffPolicy;
use App\Policies\GroupTournamentPolicy;
use App\Policies\PlayerPolicy;
use App\Policies\TeamPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Team::class                   => TeamPolicy::class,
        Player::class                 => PlayerPolicy::class,
        GroupTournament::class        => GroupTournamentPolicy::class,
        GroupGameRegular::class       => GroupGameRegularPolicy::class,
        GroupTournamentPlayoff::class => GroupTournamentPlayoffPolicy::class,
        GroupGamePlayoff::class       => GroupGamePlayoffPolicy::class,
    ];

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
