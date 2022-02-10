<?php

namespace Mafhhend\LaravelMobileAuth\ServiceProvider;

use Illuminate\Support\ServiceProvider;
use Mafhhend\LaravelMobileAuth\LaravelMobileAuth;

class LaravelMobileAuthServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind("LaravelMobileAuth", function () {
            return new LaravelMobileAuth();
        });
        // $this->mergeConfigFrom(__DIR__ . '/../config/laravelMobileAuth.php', "laravel-mobile-auth");
    }
    public function boot()
    {

        $this->_loadRoutes();
        $this->_loadViews();
        $this->_loadMigrations();
    }

    public function _loadRoutes()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }
    public function _loadViews()
    {

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path("views/vendor/LaravelMobileAuth")
        ], "laravel-mobile-auth-views");
        $this->loadViewsFrom(__DIR__ . "/../resources/views", 'laravel-mobile-auth');
    }

    public function _loadMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . "/../database/migrations");
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path("migrations")
        ], "laravel-mobile-auth-migrations");
    }
}
