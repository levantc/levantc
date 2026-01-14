<?php

namespace Sadeem\Core\Module\DTOs;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;

/**
 * Base Data Transfer Object.
 *
 * Provides a common contract for building DTOs from arrays.
 * All DTOs should extend this class.
 */
abstract class DTO
{
    /**
     * Optional Eloquent model for update operations.
     *
     * @var Model|null
     */
    protected ?Model $model = null;

    /**
     * Convert DTO to array for filling the model.
     *
     * @return array
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }

    /**
     * Convert DTO to JSON.
     *
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    /**
     * Attach a model to this DTO (for updates).
     */
    public function setModel(Model $model): static
    {
        $this->model = $model;
        return $this;
    }

    /**
     * Retrieve the attached model if any.
     */
    public function getModel(): ?Model
    {
        return $this->model;
    }

    /**
     * Create a DTO instance from an Eloquent model.
     *
     * @param Model $model
     * @return static
     * @throws ReflectionException
     */
    public static function fromModel(Model $model): static
    {
        // Initialize an array to hold model data
        $data = [];

        // Use reflection to get all properties of the DTO class
        $reflection = new ReflectionClass(static::class);
        $properties = array_map(fn($prop) => $prop->getName(), $reflection->getProperties());

        // Map model attributes to DTO properties if they exist
        foreach ($properties as $property) {
            if (isset($model->$property)) {
                $data[$property] = $model->$property;
            }
        }

        // Create the DTO from the array of model data
        $dto = static::fromArray($data);

        // Attach the original model to the DTO (useful for updates)
        if (property_exists($dto, 'model')) {
            $dto->setModel($model);
        }

        // Return the DTO instance
        return $dto;
    }

    /**
     * Build a DTO instance from an associative array.
     *
     * Matches array keys with constructor parameters.
     * If a parameter type is a subclass of BaseDTO,
     * it will be recursively instantiated.
     *
     * @param array<string, mixed> $data
     * @return static
     * @throws ReflectionException
     */
    public static function fromArray(array $data): static
    {
// Create a reflection of the current DTO class
        $class = new ReflectionClass(static::class);
        $constructor = $class->getConstructor();

        // If the class has no constructor, just return a new instance
        if (! $constructor) {
            return new static();
        }

        $params = [];

        // Iterate over constructor parameters to map data
        foreach ($constructor->getParameters() as $param) {
            $name = $param->getName();
            $type = $param->getType();

            // Handle nested DTOs if a parameter type is a subclass of DTO
            if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
                $paramClass = $type->getName();

                if (is_subclass_of($paramClass, DTO::class)) {
                    // Recursively instantiate nested DTO if data exists, otherwise null
                    $params[] = isset($data[$name])
                        ? $paramClass::fromArray($data[$name])
                        : null;
                    continue;
                }
            }

            // Map value from array if exists
            if (array_key_exists($name, $data)) {
                $params[] = $data[$name];
            }
            // Use default value if parameter has one
            elseif ($param->isDefaultValueAvailable()) {
                $params[] = $param->getDefaultValue();
            }
            // Use null if parameter allows null
            elseif ($type instanceof ReflectionNamedType && $type->allowsNull()) {
                $params[] = null;
            }
            // Otherwise throw an exception for missing required data
            else {
                throw new InvalidArgumentException("Missing value for parameter '$name' in " . static::class);
            }
        }

        // Instantiate the DTO with the mapped constructor parameters
        return $class->newInstanceArgs($params);
    }
}
