<?php

namespace Sadeem\Core\Module\Contracts\Repositories;

use Sadeem\Core\Module\DTOs\DTO;
use Illuminate\Database\Eloquent\Model;
use Sadeem\Core\Module\Responses\Response;

/**
 * RepositoryInterface
 *
 * @template TModel of Model
 * @template TDTO of DTO
 */
interface ModelRepositoryInterface extends RepositoryInterface
{
    /**
     * Retrieve all models as DTOs.
     *
     * @return TDTO[] List of DTOs representing the model instances
     */
    public function all(): array;

    /**
     * Save a model with DTO data only.
     *
     * @param DTO $dto Data Transfer Object specific to the operation.
     * @return DTO The saved DTO instance.
     */
    public function save(DTO $dto): DTO;

    /**
     * Destroy a model using data provided by a DTO.
     *
     * @param DTO $dto Data Transfer Object specific to the operation.
     * @return Response The destroyed instance.
     */
    public function destroy(DTO $dto): Response;
}
