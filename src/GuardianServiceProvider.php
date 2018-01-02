<?php

namespace MichaelCooke\Guardian;

use Illuminate\Support\ServiceProvider;

class GuardianServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrations();
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('guardian.permission', function($app) {
            return new Permission;
        });

        $this->app->singleton('guardian.role', function($app) {
            return new Role;
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
            'guardian.role',
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
