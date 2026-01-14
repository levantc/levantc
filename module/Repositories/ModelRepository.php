<?php

namespace Sadeem\Core\Module\Repositories;

use Exception;
use LogicException;
use Sadeem\Core\Module\Contracts\Repositories\ModelRepositoryInterface;
use Sadeem\Core\Module\DTOs\DTO;
use Illuminate\Database\Eloquent\Model;
use ReflectionException;
use Sadeem\I18n\Module\Enums\UseCases\Language\DestroyLanguageResult;
use Sadeem\I18n\Module\Responses\Language\DestroyLanguageResponse;

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
     * Convert a given Eloquent Model instance into its corresponding DTO.
     *
     * @param Model $model The Eloquent model instance to convert
     * @return DTO The corresponding Data Transfer Object
     */
    abstract protected function mapToDTO(Model $model): DTO;

    /**
     * Retrieve all models as DTOs.
     *
     * @return TDTO[] List of DTOs representing the model instances
     */
    public function all(): array
    {
        $models = $this->model->all();
        $dtos = [];
        foreach ($models as $model) {
            $dtos[] = $this->mapToDTO($model);
        }
        return $dtos;
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
        // Filter out any null values from the DTO to avoid overwriting existing model data
        // $data = array_filter($dto->toArray(), fn($value) => !is_null($value));
        $data = $dto->toArray();

        // Use the model attached to the DTO if it exists (Update), otherwise create a new model instance (Create)
        $model = $dto->getModel() ?? $this->model->newInstance($data);

        // Fill the model with data from the DTO
        $model->fill($data);

        // Save the model to the database
        $model->save();

        // Return a fresh DTO instance from the saved model to ensure updated data is reflected
        return $dto::fromModel($model->fresh());
    }

    /**
     * Delete a model using data provided by a DTO.
     *
     * @param DTO $dto Data Transfer Object specific to the operation.
     * @return DestroyLanguageResponse
     */
    public function destroy(DTO $dto): DestroyLanguageResponse
    {
        // Default result assumes a successful deletion
        $result = DestroyLanguageResult::SUCCESS;

        // Retrieve the model attached to the DTO (must exist for deletion)
        $model = $dto->getModel();
        if (! $model) {
            throw new LogicException('Cannot delete a model without an attached instance.');
        }

        // Determine whether to force delete or perform a soft delete
        try {
            if (method_exists($model, 'forceDelete') && ($dto->force ?? false)) {
                $model->forceDelete();
            } else {
                $model->delete();
            }
        } catch (Exception) {
            // If any exception occurs during deletion, mark the operation as failed
            $result = DestroyLanguageResult::FAILED;
        }

        // Return a typed response representing the outcome of the delete operation
        return new DestroyLanguageResponse($result, null);
    }
}
