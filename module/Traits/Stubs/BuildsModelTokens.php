<?php

namespace Eta\Core\Module\Traits\Stubs;

use Illuminate\Support\Str;

trait BuildsModelTokens
{
    protected function normalizeModel(string $model): string
    {
        return Str::studly(Str::snake($model));
    }

    protected function buildModelMap(string $model): array
    {
        $model = $this->normalizeModel($model);

        $modelLower       = Str::camel($model);
        $modelSnake       = Str::snake($model);
        $modelKebab       = Str::kebab($model);

        $modelPlural      = Str::pluralStudly($model);
        $modelLowerPlural = Str::camel($modelPlural);
        $modelSnakePlural = Str::snake($modelPlural);
        $modelKebabPlural = Str::kebab($modelPlural);

        $modelUpperCase = Str::upper(Str::replace('_', '', $modelSnake));
        $modelLowerCase = Str::lower(Str::replace('_', '', $modelSnake));

        return [
            // Singular (canonical + aliases)
            '{{modelSingular}}' => $model,
            '{{model}}'         => $model,       // alias (frontend-friendly)
            '{{modelStudly}}'   => $model,       // alias (explicit naming)

            // Singular variants
            '{{modelLower}}' => $modelLower,
            '{{modelCamel}}' => $modelLower,     // alias (clearer for frontend)
            '{{modelSnake}}' => $modelSnake,
            '{{modelKebab}}' => $modelKebab,

            // Plural (canonical + aliases)
            '{{modelPlural}}'      => $modelPlural,
            '{{modelLowerPlural}}' => $modelLowerPlural,
            '{{modelCamelPlural}}' => $modelLowerPlural, // alias
            '{{modelSnakePlural}}' => $modelSnakePlural,
            '{{modelKebabPlural}}' => $modelKebabPlural,

            // Utility formats
            '{{modelUpperCase}}' => $modelUpperCase,
            '{{modelLowerCase}}' => $modelLowerCase,
        ];
    }

    protected function replaceTokens(string $text, array $map): string
    {
        return str_replace(array_keys($map), array_values($map), $text);
    }
}
