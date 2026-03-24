<?php
namespace Eta\Core\Module\Contracts\UseCases;

use Eta\Core\Module\DTOs\DTO;
use Eta\Core\Module\Responses\Response;

/**
 * UseCaseContract
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
     * @param  ?DTO $data
     * @return Response Response object implementing Response
     */
    public function handle(?DTO $data): Response;
}
