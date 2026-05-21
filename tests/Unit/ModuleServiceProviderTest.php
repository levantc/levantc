<?php

use Levantc\Factories\RepositoryFactory;
use Levantc\Factories\UseCaseFactory;

it('registers core factories as singletons', function () {
    expect(app(UseCaseFactory::class))->toBe(app(UseCaseFactory::class))
        ->and(app(RepositoryFactory::class))->toBe(app(RepositoryFactory::class));
});

it('merges levantc configuration', function () {
    expect(config('levantc'))->toBeArray()
        ->and(config('levantc.locale_model'))->toBeNull();
});
