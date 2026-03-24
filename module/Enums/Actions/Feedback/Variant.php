<?php

namespace Eta\Core\Module\Enums\Actions\Feedback;

/**
 * Enum ToastVariant
 *
 * Defines the allowed visual/semantic variants
 * for toast notifications.
 *
 * This enum ensures:
 * - Type safety
 * - Centralized control over supported toast types
 * - Consistency between backend and frontend
 */
enum Variant: string
{
    /**
     * Indicates a successful operation.
     */
    case SUCCESS = 'success';

    /**
     * Indicates a failed operation or error.
     */
    case ERROR = 'error';

    /**
     * Indicates a warning that requires user attention.
     */
    case WARNING = 'warning';

    /**
     * Indicates informational feedback.
     */
    case INFO = 'info';
}
