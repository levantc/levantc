<?php

namespace Eta\Core\Module\Listeners;

use Eta\Core\Module\Events\ShowToast;

class ShowToastNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ShowToast $event): void
    {
        // No-op: Broadcasting will be handled by Echo on the frontend.
        // You may log or perform side effects here if needed.
    }
}
