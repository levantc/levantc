<?php

namespace Eta\Core\Module\Contracts\Result;

use Eta\Core\Module\Enums\Actions\Feedback\Variant;

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
     *
     * @param array $context
     * @return string
     */
    public function title(array $context = []): string;

    /**
     * Get the feedback message for the operation.
     *
     * @param array $context
     * @return string
     */
    public function message(array $context = []): string;

    /**
     * Get the feedback message for the operation.
     *
     * @return Variant
     */
    public function variant(): Variant;

    /**
     * Get the HTTP status code associated with the result.
     *
     * @return int
     */
    public function statusCode(): int;

    /**
     * Determine if the operation was successful.
     *
     * @return bool
     */
    public function isSuccess(): bool;
}
