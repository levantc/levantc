<?php

namespace Sadeem\Core\Module\Repositories;

use Sadeem\Core\Module\Contracts\Repositories\RepositoryInterface;
use Sadeem\Core\Module\DTOs\DTO;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Repository
 *
 * Base repository implementation.
 * Provides standard CRUD operations for Eloquent models.
 *
 * @template TModel of Model
 * @template TDTO of DTO
 */
abstract class Repository implements RepositoryInterface
{
    //
}
