<?php

namespace Sadeem\Core\Module\Contracts\UseCases;

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
     * Get the feedback message for the operation.
     *
     * @return array{title: string, message: string}
     */
    public function message(): array;

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
