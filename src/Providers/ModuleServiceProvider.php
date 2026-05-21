<?php

namespace Levantc\Providers;

use Illuminate\Support\ServiceProvider;
use Levantc\Factories\RepositoryFactory;
use Levantc\Factories\UseCaseFactory;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/levantc.php', 'levantc');

        $this->app->singleton(UseCaseFactory::class, function ($app) {
            return new UseCaseFactory($app);
        });

        $this->app->singleton(RepositoryFactory::class, function ($app) {
            return new RepositoryFactory($app);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/levantc.php' => config_path('levantc.php'),
            ], 'levantc-config');
        }
    }
}
