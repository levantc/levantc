<?php

namespace Sadeem\Core\Module\Factories;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container;
use InvalidArgumentException;

/**
 * RepositoryFactory
 *
 * Factory responsible for creating repository instances dynamically.
 * Follows the convention: Interface → Concrete class (UserRepositoryInterface → UserRepository)
 */
class RepositoryFactory
{
    /**
     * The Laravel service container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * Constructor
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Create a repository instance dynamically from an interface.
     *
     * @param string $interface Fully qualified interface name
     * @return mixed
     *
     * @throws BindingResolutionException
     */
    public function make(string $interface): mixed
    {
        if (!interface_exists($interface)) {
            throw new InvalidArgumentException("Interface $interface not found.");
        }

        // Convert Interface name to Repository class name (UserRepositoryInterface → UserRepository)
        $repositoryClass = str_replace('Interface', '', $interface);
        $repositoryClass = str_replace('\\Contracts', '', $repositoryClass);

        if (!class_exists($repositoryClass)) {
            throw new InvalidArgumentException("Repository class $repositoryClass not found for interface $interface.");
        }
        if (!class_exists($repositoryClass)) {
            throw new InvalidArgumentException("Repository class $repositoryClass not found for interface $interface.");
        }

        // Use the container to resolve the repository instance (supports DI)
        return $this->container->make($repositoryClass);
    }
}
