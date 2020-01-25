<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Team'                   => 'App\Policies\TeamPolicy',
        'App\Models\Player'                 => 'App\Policies\PlayerPolicy',
        'App\Models\GroupTournament'        => 'App\Policies\GroupTournamentPolicy',
        'App\Models\GroupGameRegular'       => 'App\Policies\GroupGameRegularPolicy',
        'App\Models\GroupTournamentPlayoff' => 'App\Policies\GroupTournamentPlayoffPolicy',
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
