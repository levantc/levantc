<?php

namespace Sadeem\Core\Module\Contracts\Repositories;

use Sadeem\Core\Module\DTOs\DTO;
use Illuminate\Database\Eloquent\Model;

/**
 * RepositoryInterface
 *
 * @template TModel of Model
 * @template TDTO of DTO
 */
interface ModelRepositoryInterface extends RepositoryInterface
{
    /**
     * Save a model with DTO data only.
     *
     * @param DTO $dto Data Transfer Object specific to the operation.
     * @return DTO The saved DTO instance.
     */
    public function save(DTO $dto): DTO;
}
