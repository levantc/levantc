<?php

namespace Eta\Core\Module\Contracts\Auth;

/**
 * Interface Actor
 *
 * Represents the authenticated entity interacting with the system.
 * This could be an Admin, Employee, Customer, Partner, etc.
 *
 * The Actor encapsulates:
 * - Its identity
 * - Its user type (guard)
 * - Its roles within that type
 *
 * This abstraction allows policies and authorization logic
 * to remain independent from specific user models.
 */
interface Actor
{
    /**
     * Unique identifier of the actor.
     */
    public function id(): int|string;

    /**
     * Returns the actor type (authentication guard).
     *
     * Examples:
     *  - admin
     *  - employee
     *  - customer
     *  - partner
     */
    public function type(): string;

    /**
     * Returns all roles assigned to the actor within its type.
     *
     * Example:
     *  ['manager', 'support']
     */
    public function roles(): array;

    /**
     * Determine whether the actor has a specific role.
     */
    public function hasRole(string $role): bool;

    /**
     * Determine whether the actor has at least one role
     * from the provided list.
     */
    public function hasAnyRole(array $roles): bool;

    /**
     * Determine whether the actor belongs to a specific type.
     */
    public function isType(string $type): bool;

    /**
     * Determine whether the actor belongs to any of the given types.
     */
    public function isAnyType(array $types): bool;
}
