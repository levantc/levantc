<?php

namespace Levantc\Services;

use Illuminate\Contracts\Container\BindingResolutionException;
use Levantc\Contracts\UseCases\UseCaseContract;
use Levantc\DTOs\DTO;
use Levantc\Factories\RepositoryFactory;
use Levantc\Responses\Response;

/**
 * Class UseCase
 *
 * Base abstract class for all UseCases.
 * Provides a common repository property and helpers for managing models.
 *
 * @template TInput of DTO
 * @template TOutput of UseCaseContract
 */
abstract class UseCase implements UseCaseContract
{
    /**
     * The repository instance
     */
    protected mixed $repository;

    /**
     * The RepositoryFactory instance
     */
    protected RepositoryFactory $factory;

    /**
     * Constructor
     */
    public function __construct(RepositoryFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Dynamically set the repository for this Usecase
     *
     * @param  string  $interface  Fully qualified interface name
     *
     * @throws BindingResolutionException
     */
    protected function setRepository(string $interface): void
    {
        $this->repository = $this->factory->make($interface);
    }

    /**
     * Execute the business logic of the use case.
     *
     * @param  ?DTO  $data  Input DTO
     * @return Response Response object implementing Response
     */
    abstract public function handle(?DTO $data): Response;
}
