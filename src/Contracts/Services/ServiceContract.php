<?php

namespace Levantc\Contracts\Services;

use Levantc\DTOs\DTO;
use Levantc\Responses\Response;

/**
 * UseCaseContract
 *
 * Base interface for all use cases.
 *
 * @template TInput of DTO
 * @template TOutput of Response
 */
interface ServiceContract
{
    /**
     * Execute the use case logic.
     *
     * @return Response Response object implementing Response
     */
    public function handle(?DTO $data): Response;
}
