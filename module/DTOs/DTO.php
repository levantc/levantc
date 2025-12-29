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
     * Create a DTO instance from an Eloquent model.
     *
     * @param Model $model
     * @return static
     * @throws ReflectionException
     */
    public static function fromModel(Model $model): static
    {
        $data = [];

        $reflection = new ReflectionClass(static::class);
        $properties = array_map(fn($prop) => $prop->getName(), $reflection->getProperties());

        foreach ($properties as $property) {
            if (isset($model->$property)) {
                $data[$property] = $model->$property;
            }
        }

        return static::fromArray($data);
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
        $class = new ReflectionClass(static::class);
        $constructor = $class->getConstructor();

        if (! $constructor) {
            return new static();
        }

        $params = [];

        foreach ($constructor->getParameters() as $param) {
            $name = $param->getName();
            $type = $param->getType();

            if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
                // If parameter type is a class
                $paramClass = $type->getName();

                if (is_subclass_of($paramClass, DTO::class)) {
                    // Nested DTO
                    $params[] = isset($data[$name])
                        ? $paramClass::fromArray($data[$name])
                        : null;
                    continue;
                }
            }

            if (array_key_exists($name, $data)) {
                $params[] = $data[$name];
            } elseif ($param->isDefaultValueAvailable()) {
                $params[] = $param->getDefaultValue();
            } else {
                throw new InvalidArgumentException("Missing value for parameter '$name' in " . static::class);
            }
        }

        return $class->newInstanceArgs($params);
    }
}
