<?php

namespace Sadeem\Core\Module\Actions\Notifier;

use Sadeem\Core\Module\Actions\Feedback\Toast;

/**
 * Notifier is responsible for delivering user feedback
 * in real-time or through any other delivery mechanism.
 *
 * It does NOT know:
 * - UI framework
 * - Transport layer
 * - Presentation details
 */
interface Notifier
{
    /**
     * Dispatch the given toast feedback immediately.
     *
     * This is a fire-and-forget operation.
     */
    public function notify(Toast $toast): void;
}
