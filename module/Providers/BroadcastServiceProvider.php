<?php
namespace Sadeem\Core\Module\Providers;

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

        // Load the channels.php file from the routes folder
        // This is where you define any private or presence channels
        // For ShowToast's public channel, no extra definition is needed
        require base_path('routes/channels.php');
    }
}
