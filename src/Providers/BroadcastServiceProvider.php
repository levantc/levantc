<?php

namespace Levantc\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register all the necessary broadcast routes in Laravel
        // Without this, no Event can be broadcasted via the broadcasting system
        Broadcast::routes();

        $channels = base_path('routes/channels.php');

        if (is_file($channels)) {
            require $channels;
        }
    }
}
