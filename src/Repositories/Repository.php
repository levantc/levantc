<?php

namespace Levantc\Repositories;

use Illuminate\Database\Eloquent\Model;
use Levantc\Contracts\Repositories\RepositoryInterface;
use Levantc\DTOs\DTO;

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
