<?php

namespace Levantc\Traits\Concerns;

use Illuminate\Support\Facades\DB;
use Throwable;

trait HasTransaction
{
    /**
     * Execute a callback within a database transaction.
     *
     * @throws Throwable
     */
    protected function transaction(callable $callback): mixed
    {
        return DB::transaction($callback);
    }
}
