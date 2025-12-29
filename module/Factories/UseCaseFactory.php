<?php

namespace Sadeem\Core\Module\Factories;

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
     *   The Laravel service container used to resolve dependencies.
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Create an instance of an Usecase
     *
     * @param string $usecaseClass
     *   Fully qualified class name of the Usecase to instantiate.
     * @return mixed
     *
     * This method leverages Laravel's container to automatically
     * inject all required dependencies of the Usecase.
     * @throws BindingResolutionException
     */
    public function make(string $usecaseClass): mixed
    {
        if (!class_exists($usecaseClass)) {
            throw new InvalidArgumentException("Usecase class $usecaseClass not found.");
        }
        // Use Laravel's service container to resolve the Usecase class
        return $this->container->make($usecaseClass);
    }
}
