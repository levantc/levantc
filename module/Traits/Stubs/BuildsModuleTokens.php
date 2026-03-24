<?php

namespace Eta\Core\Module\Traits\Stubs;

use Illuminate\Support\Str;

trait BuildsModuleTokens
{
    /**
     * Build a consistent token map for module-related placeholders.
     *
     * Supports both backend and frontend stub needs:
     * - backend labels / namespaces (Studly, Label, Namespace)
     * - frontend aliases / routes / paths (lowercase, folder, key)
     *
     * @param string $moduleKey  Config key (e.g. "geography", "ums")
     * @param array  $moduleMeta Module config entry from config/modules.php
     */
    protected function buildModuleMap(string $moduleKey, array $moduleMeta = []): array
    {
        $moduleKey = $this->normalizeModuleKey($moduleKey);

        $moduleLabel = (string) ($moduleMeta['label'] ?? Str::studly($moduleKey));
        $moduleFolder = (string) ($moduleMeta['folder'] ?? $moduleKey);
        $moduleNamespace = (string) ($moduleMeta['namespace'] ?? ('Eta\\' . Str::studly($moduleKey)));

        // Naming variants
        $moduleStudly = Str::studly($moduleKey);    // Geography
        $moduleCamel  = Str::camel($moduleKey);     // geography (or userManagement)
        $moduleSnake  = Str::snake($moduleKey);     // geography
        $moduleKebab  = Str::kebab($moduleKey);     // geography

        // Optional plural variants (useful later if needed in routes/pages)
        $moduleStudlyPlural = Str::pluralStudly($moduleStudly);
        $moduleCamelPlural  = Str::camel($moduleStudlyPlural);
        $moduleSnakePlural  = Str::snake($moduleStudlyPlural);
        $moduleKebabPlural  = Str::kebab($moduleStudlyPlural);

        return [
            /**
             * Backward compatibility:
             * Old stubs may already use {{module}} to mean display label.
             */
            '{{module}}' => $moduleLabel,

            /**
             * Canonical module tokens (recommended going forward)
             */
            '{{moduleKey}}'       => $moduleKey,        // geography
            '{{moduleLabel}}'     => $moduleLabel,      // Geography
            '{{moduleFolder}}'    => $moduleFolder,     // geography
            '{{moduleNamespace}}' => $moduleNamespace,  // Eta\Geography

            /**
             * Naming variants (frontend/backend friendly)
             */
            '{{moduleStudly}}' => $moduleStudly, // Geography
            '{{moduleCamel}}'  => $moduleCamel,  // geography / userManagement
            '{{moduleSnake}}'  => $moduleSnake,  // geography / user_management
            '{{moduleKebab}}'  => $moduleKebab,  // geography / user-management

            /**
             * Aliases for convenience / legacy usage in stubs
             */
            '{{moduleLower}}'    => $moduleKey,   // same as key
            '{{moduleRoute}}'    => $moduleKey,   // route namespace alias
            '{{moduleSingular}}' => $moduleCamel, // legacy compatibility (your current usage)

            /**
             * Optional plural variants
             */
            '{{moduleStudlyPlural}}' => $moduleStudlyPlural,
            '{{moduleCamelPlural}}'  => $moduleCamelPlural,
            '{{moduleSnakePlural}}'  => $moduleSnakePlural,
            '{{moduleKebabPlural}}'  => $moduleKebabPlural,
        ];
    }

    /**
     * Normalize module key into a stable lowercase identifier.
     * Examples:
     * - "UMS" => "ums"
     * - " Geography " => "geography"
     */
    protected function normalizeModuleKey(string $moduleKey): string
    {
        return Str::of($moduleKey)->trim()->lower()->toString();
    }
}
