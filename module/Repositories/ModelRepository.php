<?php

namespace Sadeem\Core\Module\Repositories;

use Sadeem\Core\Module\Contracts\Repositories\ModelRepositoryInterface;
use Sadeem\Core\Module\DTOs\DTO;
use Illuminate\Database\Eloquent\Model;
use ReflectionException;

/**
 * Class Repository
 *
 * Base repository implementation.
 * Provides standard CRUD operations for Eloquent models.
 *
 * @template TModel of Model
 * @template TDTO of DTO
 */
abstract class ModelRepository implements ModelRepositoryInterface
{
    /**
     * The Eloquent model instance.
     *
     * @var Model
     */
    protected Model $model;

    /**
     * Repository constructor.
     *
     * @param Model $model The Eloquent model instance.
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Save a model with data from DTO.
     *
     * @param DTO $dto Data Transfer Object specific to the operation.
     * @return DTO The saved DTO instance.
     * @throws ReflectionException
     */
    public function save(DTO $dto): DTO
    {
        // Fill the model with data from DTO
        $this->model->fill($dto->toArray());

        // Save the model to the database
        $this->model->save();

        // Return a fresh DTO instance from the updated model
        return $dto::fromModel($this->model->fresh());
    }
}
