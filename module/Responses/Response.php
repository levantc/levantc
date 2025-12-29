<?php

namespace Sadeem\Core\Module\Responses;

use Sadeem\Core\Module\DTOs\DTO;
use Sadeem\Core\Module\Contracts\UseCases\ResultContract;

/**
 * Generic Response class for any UseCase operation.
 *
 * This class wraps:
 *   1. The operation result (any Enum implementing Result)
 *   2. An optional DTO data object returned by the UseCase
 *
 * Provides helper methods to access:
 *   - HTTP status code
 *   - User-facing messages
 *   - Success check
 */
readonly class Response
{
    /**
     * Constructor
     *
     * @param ResultContract $result
     *   The result Contract of the UseCase (e.g., SaveUserResult, UpdateUserResult, etc.)
     * @param DTO|null $data
     */
    public function __construct(
        public ResultContract $result,
        public ?DTO   $data = null,
    ) {}

    /**
     * Get the HTTP status code associated with the operation result.
     *
     * @return int
     */
    public function statusCode(): int
    {
        return $this->result->statusCode();
    }

    /**
     * Get the user-facing feedback message associated with the operation result.
     *
     * @return array{title: string, message: string}
     */
    public function message(): array
    {
        return $this->result->message();
    }

    /**
     * Determine if the operation was successful.
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->result->isSuccess();
    }
}
