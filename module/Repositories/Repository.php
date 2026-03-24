<?php

namespace Eta\Core\Module\Repositories;

use Eta\Core\Module\Contracts\Repositories\RepositoryInterface;
use Eta\Core\Module\DTOs\DTO;
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
