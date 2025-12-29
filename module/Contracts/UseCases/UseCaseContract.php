<?php
namespace Sadeem\Core\Module\Contracts\UseCases;

use Sadeem\Core\Module\DTOs\DTO;
use Sadeem\Core\Module\Responses\Response;

/**
 * UsecaseContract
 *
 * Base interface for all use cases.
 *
 * @template TInput of DTO
 * @template TOutput of Response
 */
interface UseCaseContract
{
    /**
     * Execute the use case logic.
     *
     * @param  DTO $data
     * @return Response Response object implementing Response
     */
    public function handle(DTO $data): Response;
}
