<?php
namespace Sadeem\Core\Module\Services;

use Sadeem\Core\Module\DTOs\DTO;
use Sadeem\Core\Module\Factories\RepositoryFactory;
use Sadeem\Core\Module\Responses\Response;
use Sadeem\Core\Module\Contracts\UseCases\UseCaseContract;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * Class UseCase
 *
 * Base abstract class for all UseCases.
 * Provides a common repository property and helpers for managing models.
 *
 * @template TInput of DTO
 * @template TOutput of UseCaseContract
 */
abstract class UseCase implements UsecaseContract
{
    /**
     * The repository instance
     *
     * @var mixed
     */
    protected mixed $repository;

    /**
     * The RepositoryFactory instance
     *
     * @var RepositoryFactory
     */
    protected RepositoryFactory $factory;

    /**
     * Constructor
     *
     * @param RepositoryFactory $factory
     */
    public function __construct(RepositoryFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Dynamically set the repository for this Usecase
     *
     * @param string $interface Fully qualified interface name
     * @return void
     * @throws BindingResolutionException
     */
    protected function setRepository(string $interface): void
    {
        $this->repository = $this->factory->make($interface);
    }

    /**
     * Execute the business logic of the use case.
     *
     * @param DTO $data Input DTO
     * @return Response Response object implementing Response
     */
    abstract public function handle(DTO $data): Response;
}
