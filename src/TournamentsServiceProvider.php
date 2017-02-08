<?php

namespace Xoco70\LaravelTournaments;

use Illuminate\Support\ServiceProvider;

class TournamentsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/web.php');
        $this->loadViewsFrom(__DIR__ . '/views', 'laravel-tournaments');
        $this->loadMigrationsFrom(__DIR__ . '/migrations/');
        $this->loadTranslationsFrom(__DIR__.'/translations', 'courier');

//        $this->publishes([__DIR__ . '/views' => base_path('resources/views/vendor/laravel-tournaments')]);
        $this->publishes([__DIR__ . '/config/laravel-tournaments.php' => config_path('laravel-tournaments.php'),]);
        $this->publishes([__DIR__ . '/migrations' => $this->app->databasePath() . '/migrations'], 'migrations');
        $this->publishes([__DIR__ . '/seeds' => $this->app->databasePath() . '/seeds'], 'seeds');
        $this->publishes([__DIR__ . '/factories' => $this->app->databasePath() . '/factories'], 'factories');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Replace HTML:: y FORM:: by native html
        include __DIR__.'/web.php';
        $this->app->make(TreeDemoController::class);
    }
}
