<?php

namespace Eta\Core\Module\Actions\Notifier;

use Eta\Core\Module\Actions\Feedback\Toast;

/**
 * Null implementation of Notifier.
 *
 * Useful for:
 * - Local development
 * - Testing
 * - Disabling realtime feedback without changing code
 */
final class NullNotifier implements Notifier
{
    /**
     * Dispatch the given toast feedback immediately.
     *
     * This is a fire-and-forget operation.
     */
    public function notify(Toast $toast): void
    {
        // Intentionally left blank.
    }
}
