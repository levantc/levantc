<?php
namespace Sadeem\Core\Module\Services;

use Sadeem\Core\Module\Contracts\Services\ServiceContract;
use Sadeem\Core\Module\DTOs\DTO;
use Sadeem\Core\Module\Responses\Response;
use Sadeem\Core\Module\Traits\Concerns\HasTransaction;

/**
 * Class Service
 *
 * Base abstract class for all Services.
 * A Service acts as an orchestrator that can run multiple UseCases within a single operation.
 * It provides a common structure for complex business logic that spans across multiple domains.
 */
abstract class Service implements ServiceContract
{
    use HasTransaction;

    /**
     * Execute the business logic of the service.
     * This usually involves coordinating multiple UseCases.
     *
     * @param ?DTO $data Input DTO
     * @return Response Response object implementing Response
     */
    abstract public function handle(?DTO $data): Response;
}
