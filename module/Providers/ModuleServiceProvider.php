<?php
namespace Sadeem\Core\Module\Providers;

use Illuminate\Support\ServiceProvider;
use Sadeem\Core\Module\Factories\RepositoryFactory;
use Sadeem\Core\Module\Factories\UseCaseFactory;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register UseCaseFactory as a singleton for consistent use across the app
        $this->app->singleton(UsecaseFactory::class, function ($app) {
            return new UsecaseFactory($app);
        });

        // Register RepositoryFactory as a singleton for consistent use across the app
        $this->app->singleton(RepositoryFactory::class, function ($app) {
            return new RepositoryFactory($app);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
