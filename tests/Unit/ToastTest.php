<?php

use Levantc\Actions\Feedback\Toast;
use Levantc\Enums\Actions\Feedback\Variant;

it('serializes toast feedback for transport', function () {
    $toast = Toast::make(Variant::SUCCESS, 'Saved', 'Your changes were stored.');

    expect($toast->toArray())->toBe([
        'variant' => 'success',
        'title' => 'Saved',
        'message' => 'Your changes were stored.',
        'actions' => [],
    ]);
});
