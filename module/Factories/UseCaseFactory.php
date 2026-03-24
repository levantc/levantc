<?php

namespace Eta\Core\Module\Factories;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container;
use InvalidArgumentException;

/**
 * UsecaseFactory
 *
 * A factory class responsible for creating instances of Usecases dynamically.
 * It uses Laravel's service container to automatically resolve dependencies.
 */
class UseCaseFactory
{
    /**
     * The Laravel service container instance.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * Constructor
     *
     * @param Container $container
     *   The Laravel service container is used to resolve dependencies.
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Create an instance of an UseCase
     *
     * @param string $useCaseClass
     *   Fully qualified class name of the UseCase to instantiate.
     * @return mixed
     *
     * This method leverages Laravel's container to automatically
     * inject all required dependencies of the UseCase.
     * @throws BindingResolutionException
     */
    public function make(string $useCaseClass): mixed
    {
        if (!class_exists($useCaseClass)) {
            throw new InvalidArgumentException("UseCase class $useCaseClass not found.");
        }
        // Use Laravel's service container to resolve the UseCase class
        return $this->container->make($useCaseClass);
    }
}
