<?php

namespace Sadeem\Core\Module\Enums\Actions;

/**
 * Enum ActionType
 *
 * Defines the possible types of actions that a Result can trigger.
 */
enum Type: string
{
    /**
     * Redirect to a specific URL.
     */
    case REDIRECT = 'redirect';

    /**
     * Trigger a frontend event (e.g., open modal, refresh section).
     */
    case EVENT = 'event';

    /**
     * Execute a backend callback or method.
     */
    case CALLBACK = 'callback';

    /**
     * Custom or miscellaneous action types.
     */
    case CUSTOM = 'custom';
}
