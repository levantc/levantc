<?php

namespace Levantc\Repositories;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Levantc\Contracts\Repositories\ModelRepositoryInterface;
use Levantc\DTOs\DTO;
use LogicException;
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
     */
    protected Model $model;

    /**
     * Repository constructor.
     *
     * @param  Model  $model  The Eloquent model instance.
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Convert a given Eloquent Model instance into its corresponding DTO.
     *
     * @param  Model  $model  The Eloquent model instance to convert
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
        return $this->model->get()
            ->map(fn ($model) => $this->mapToDTO($model))
            ->all();
    }

    /**
     * Save a model with data from DTO.
     *
     * @param  DTO  $dto  Data Transfer Object specific to the operation.
     * @return DTO The saved DTO instance.
     *
     * @throws ReflectionException
     */
    public function save(DTO $dto): DTO
    {
        // Some tables might need null values, so we check if filtering is disabled
        $data = $dto->toArray();

        // Filter out any null values from the DTO to avoid overwriting existing model data
        if ($dto->shouldFilterNulls()) {
            $data = array_filter($data, fn ($value) => ! is_null($value));
        }

        // Use the model attached to the DTO if it exists (Update), otherwise create a new model instance (Create)
        $model = $dto->getModel() ?? $this->model->newInstance();

        // Fill the model with data from the DTO
        $model->fill($data);

        // Save the model to the database
        $model->save();

        // Return a fresh DTO instance from the saved model to ensure updated data is reflected
        return $dto::fromModel($model->fresh(), true);
    }

    /**
     * Find a model by its primary key.
     *
     * @param  int|string  $id  The primary key of the model to retrieve.
     * @return DTO|null Returns the corresponding DTO if found, or null if not.
     */
    public function find(int|string $id): ?DTO
    {
        $model = $this->model->find($id);

        if (! $model) {
            return null;
        }

        return $this->mapToDTO($model);
    }

    /**
     * Take a snapshot of an entity by ID and map it to a Snapshot DTO.
     *
     * - Generic method works for ANY model.
     * - The UseCase or Repository provides the DTO class.
     *
     * @param  class-string<DTO>  $snapshotDtoClass
     *
     * @throws ReflectionException
     */
    public function snapshotById(int $id, string $snapshotDtoClass): ?DTO
    {
        // 1) Fetch model (Eloquent) from database
        $model = $this->model->find($id);

        // 2) If not found => no snapshot
        if (! $model) {
            return null;
        }

        // 3) Ensure the class is a DTO
        if (! is_subclass_of($snapshotDtoClass, DTO::class)) {
            throw new \InvalidArgumentException(
                'Snapshot DTO must extend '.DTO::class
            );
        }

        // 4) Build snapshot DTO from model (your DTO::fromModel handles mapping)
        return $snapshotDtoClass::fromModel($model);
    }

    /**
     * Delete a model using data provided by a DTO.
     *
     * @param  DTO  $dto  Data Transfer Object specific to the operation.
     */
    public function destroy(DTO $dto): bool
    {
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

            return true;
        } catch (Exception) {
            return false;
        }
    }
}
