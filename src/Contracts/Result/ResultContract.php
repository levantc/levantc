<?php

namespace Levantc\Contracts\Result;

use Levantc\Enums\Actions\Feedback\Variant;

/**
 * Interface resultInterface
 *
 * Defines the contract for all UseCase Result Enums.
 * Ensures that each Result Enum provides:
 *   1. A user-facing message
 *   2. An HTTP status code
 *   3. A success check
 */
interface ResultContract
{
    /**
     * Get the feedback title for the operation.
     */
    public function title(array $context = []): string;

    /**
     * Get the feedback message for the operation.
     */
    public function message(array $context = []): string;

    /**
     * Get the feedback message for the operation.
     */
    public function variant(): Variant;

    /**
     * Get the HTTP status code associated with the result.
     */
    public function statusCode(): int;

    /**
     * Determine if the operation was successful.
     */
    public function isSuccess(): bool;
}
