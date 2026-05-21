<?php

/**
 * Pest bindings for the LevantC foundation module.
 *
 * Loaded by the host platform tests/Pest.php. Tests run against the full Laravel application.
 */

use Levantc\Tests\TestCase;

pest()->extend(TestCase::class)->in(__DIR__);
