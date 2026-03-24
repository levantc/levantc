<?php
namespace Eta\Core\Module\Services;

use Eta\Core\Module\Contracts\Services\ServiceContract;
use Eta\Core\Module\DTOs\DTO;
use Eta\Core\Module\Responses\Response;
use Eta\Core\Module\Traits\Concerns\HasTransaction;

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
