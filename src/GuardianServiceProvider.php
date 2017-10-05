<?php

namespace MichaelCooke\Guardian;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Auth\Events\Registered as UserRegistered;
use MichaelCooke\Guardian\Listeners\CreateUserActivation;

class GuardianServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Dispatcher $events)
    {
        $this->loadMigrations();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('guardian.permission', function($app) {
            return new Permission;
        });

        $this->app->singleton('guardian.restriction', function($app) {
            return new Restriction;
        });

        $this->app->singleton('guardian.role', function($app) {
            return new Role;
        });

        $this->app->singleton('guardian.user', function($app) {
            return new User;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'guardian.permission',
            'guardian.restriction',
            'guardian.role',
            'guardian.user',
        ];
    }

    /**
     * Load package migrations.
     *
     * @return void
     */
    public function loadMigrations()
    {
        $this->loadMigrationsFrom(dirname(__DIR__).'/database/migrations');
    }
}
