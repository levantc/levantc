<?php
namespace Sadeem\Core\Module\Contracts\Services;

use Sadeem\Core\Module\DTOs\DTO;
use Sadeem\Core\Module\Responses\Response;

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
     * @param  ?DTO $data
     * @return Response Response object implementing Response
     */
    public function handle(?DTO $data): Response;
}
