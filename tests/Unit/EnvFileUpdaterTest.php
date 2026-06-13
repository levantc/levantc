<?php

use Illuminate\Support\Facades\File;
use Levantc\Services\Environment\EnvFileUpdater;

test('env file updater appends new variables', function () {
    $path = sys_get_temp_dir().'/env-updater-'.uniqid().'.env';
    File::put($path, "APP_NAME=LevantC\n# comment\n");

    app(EnvFileUpdater::class)->update($path, [
        'GIT_USERNAME' => 'dev-user',
        'GIT_TOKEN' => 'secret-token',
    ]);

    $contents = File::get($path);

    expect($contents)
        ->toContain("APP_NAME=LevantC\n")
        ->toContain("# comment\n")
        ->toContain("GIT_USERNAME=dev-user\n")
        ->toContain("GIT_TOKEN=secret-token\n");

    File::delete($path);
});

test('env file updater replaces existing variables without duplicating', function () {
    $path = sys_get_temp_dir().'/env-updater-'.uniqid().'.env';
    File::put($path, "GIT_USERNAME=old-user\nGIT_TOKEN=old-token\n");

    app(EnvFileUpdater::class)->update($path, [
        'GIT_USERNAME' => 'new-user',
        'GIT_TOKEN' => 'new-token-value',
    ]);

    $contents = File::get($path);

    expect($contents)
        ->toBe("GIT_USERNAME=new-user\nGIT_TOKEN=new-token-value\n")
        ->and(substr_count($contents, 'GIT_USERNAME='))->toBe(1)
        ->and(substr_count($contents, 'GIT_TOKEN='))->toBe(1);

    File::delete($path);
});

test('env file updater quotes values with special characters', function () {
    $path = sys_get_temp_dir().'/env-updater-'.uniqid().'.env';
    File::put($path, '');

    app(EnvFileUpdater::class)->update($path, [
        'GIT_BASE_URL' => 'https://git.example.com/org repo',
    ]);

    expect(File::get($path))->toBe('GIT_BASE_URL="https://git.example.com/org repo"'."\n");

    File::delete($path);
});

test('env file updater replaces exported variables', function () {
    $path = sys_get_temp_dir().'/env-updater-'.uniqid().'.env';
    File::put($path, "export GIT_USERNAME=old-user\n");

    app(EnvFileUpdater::class)->update($path, [
        'GIT_USERNAME' => 'new-user',
    ]);

    expect(File::get($path))->toBe("GIT_USERNAME=new-user\n");

    File::delete($path);
});
