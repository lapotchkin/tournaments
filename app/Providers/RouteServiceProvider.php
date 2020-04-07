<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
        $this->mapAjaxRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web/common.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web/group.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web/personal.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web/player.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web/team.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

    /**
     * @return void
     */
    protected function mapAjaxRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/ajax/group.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/ajax/personal.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/ajax/player.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/ajax/team.php'));
    }
}
