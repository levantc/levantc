<?php

namespace Sadeem\Core\Module\DTOs;

use Illuminate\Database\Eloquent\Collection;
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
     * Determine if null values should be filtered out before saving.
     */
    public function shouldFilterNulls(): bool
    {
        return true;
    }

    /**
     * Determine if the DTO has any non-empty data.
     *
     * @param array $exclude Keys to exclude from the check.
     * @return bool
     */
    public function isNotEmpty(array $exclude = ['id', 'model']): bool
    {
        foreach ($this->toArray() as $key => $value) {
            if (in_array($key, $exclude)) {
                continue;
            }

            if (!empty($value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Create a DTO instance from an Eloquent model.
     *
     * @param Model $model
     * @param bool $attachModel
     * @return static
     * @throws ReflectionException
     */
    public static function fromModel(Model $model, bool $attachModel = false): static
    {
        // Initialize an array to hold model data
        $data = [];

        // Use reflection to get all properties of the DTO class
        $reflection = new ReflectionClass(static::class);
        $properties = array_map(fn($prop) => $prop->getName(), $reflection->getProperties());

        // Map model attributes to DTO properties if they exist
        foreach ($properties as $property) {
            if (isset($model->$property)) {
                $value = $model->$property;

                // Automatically convert translations collection to array if it exists
                if ($property === 'translations' && $value instanceof Collection) {
                    $value = $value->toArray();
                }

                $data[$property] = $value;
            }
        }

        // Create the DTO from the array of model data
        $dto = static::fromArray($data);

        // Attach the original model to the DTO (useful for updates)
        if ($attachModel && property_exists($dto, 'model')) {
            $dto->setModel($model);
        }

        // Return the DTO instance
        return $dto;
    }

    /**
     * Check if the current DTO has the required fields for another DTO.
     *
     * @param string $targetDtoClass
     * @param array $additionalData
     * @return bool
     * @throws ReflectionException
     */
    public function hasRequiredFieldsFor(string $targetDtoClass, array $additionalData = []): bool
    {
        if (!is_subclass_of($targetDtoClass, self::class)) {
            return false;
        }

        $reflection = new ReflectionClass($targetDtoClass);
        $constructor = $reflection->getConstructor();

        if (!$constructor) {
            return true;
        }

        $data = array_merge($this->toArray(), $additionalData);

        foreach ($constructor->getParameters() as $param) {
            $name = $param->getName();
            $type = $param->getType();

            // Skip if the parameter is not required
            if ($param->isDefaultValueAvailable() || ($type instanceof ReflectionNamedType && $type->allowsNull())) {
                continue;
            }

            // If a required field is missing or empty in the data
            if (!isset($data[$name]) || (is_scalar($data[$name]) && trim((string)$data[$name]) === '') || (is_array($data[$name]) && empty($data[$name]))) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if the current DTO has any non-empty data for the fields required or present in another DTO.
     *
     * @param string $targetDtoClass
     * @param array $exclude Keys to exclude from the check.
     * @return bool
     * @throws ReflectionException
     */
    public function hasDataFor(string $targetDtoClass, array $exclude = ['id', 'model']): bool
    {
        if (!is_subclass_of($targetDtoClass, self::class)) {
            return false;
        }

        $reflection = new ReflectionClass($targetDtoClass);
        $constructor = $reflection->getConstructor();

        if (!$constructor) {
            return false;
        }

        $sourceData = $this->toArray();

        foreach ($constructor->getParameters() as $param) {
            $name = $param->getName();
            $type = $param->getType();

            if (in_array($name, $exclude)) {
                continue;
            }

            // We only care about fields that are required by the target
            if ($param->isDefaultValueAvailable() || ($type instanceof ReflectionNamedType && $type->allowsNull())) {
                continue;
            }

            if (isset($sourceData[$name]) && !empty($sourceData[$name])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Create a DTO instance from another DTO.
     *
     * @param DTO $dto
     * @param array $additionalData
     * @return static
     * @throws ReflectionException
     */
    public static function fromDTO(DTO $dto, array $additionalData = []): static
    {
        $data = array_merge($dto->toArray(), $additionalData);

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
        // Convert translations to array if it is a collection
        if (isset($data['translations']) && $data['translations'] instanceof Collection) {
            $data['translations'] = $data['translations']->toArray();
        }

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

