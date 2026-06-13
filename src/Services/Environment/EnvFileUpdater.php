<?php

namespace Levantc\Services\Environment;

use Illuminate\Support\Facades\File;
use RuntimeException;

class EnvFileUpdater
{
    /**
     * @param  array<string, string>  $variables
     */
    public function update(string $path, array $variables): void
    {
        if ($variables === []) {
            return;
        }

        if (! File::exists($path)) {
            File::put($path, '');
        }

        $contents = str_replace("\r\n", "\n", File::get($path));

        foreach ($variables as $key => $value) {
            $contents = $this->setVariable($contents, $key, $value);
        }

        $this->writeAtomically($path, $contents);
    }

    public function formatValue(string $value): string
    {
        if ($value === '') {
            return '';
        }

        if (preg_match('/[\s#"\']/', $value) === 1) {
            return '"'.str_replace(['\\', '"'], ['\\\\', '\\"'], $value).'"';
        }

        return $value;
    }

    private function setVariable(string $contents, string $key, string $value): string
    {
        $line = $key.'='.$this->formatValue($value);
        $pattern = '/^(export\s+)?'.preg_quote($key, '/').'=.*/m';

        if (preg_match($pattern, $contents) === 1) {
            return (string) preg_replace($pattern, $line, $contents, 1);
        }

        $contents = rtrim($contents, "\n");

        if ($contents !== '') {
            $contents .= "\n";
        }

        return $contents.$line."\n";
    }

    private function writeAtomically(string $path, string $contents): void
    {
        $normalized = str_ends_with($contents, "\n") ? $contents : $contents."\n";
        $temporaryPath = $path.'.'.uniqid('tmp_', true);

        if (File::put($temporaryPath, $normalized) === false) {
            throw new RuntimeException("Unable to write temporary environment file [{$temporaryPath}].");
        }

        if (! @rename($temporaryPath, $path)) {
            File::delete($temporaryPath);

            throw new RuntimeException("Unable to update environment file [{$path}].");
        }
    }
}
