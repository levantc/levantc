<?php

namespace Levantc\Traits\Modules;

use Illuminate\Support\Str;

/**
 * Class ModuleCatalog
 *
 * Purpose:
 * - Single source of truth for reading and resolving modules configuration.
 * - Keeps module access consistent across the entire system (Core, Console, etc.).
 *
 * Responsibilities:
 * - Read modules list from config('modules.modules').
 * - Normalize module keys.
 * - Return module metadata by key.
 * - Resolve module base path into absolute disk path.
 */
class ModuleCatalog
{
    /**
     * Return all configured modules from config.
     *
     * Expected config structure:
     * config('modules.modules') => [
     *   'ums' => ['label' => 'UMS', 'base_path' => 'modules/ums', ...],
     *   ...
     * ]
     */
    public function all(): array
    {
        return (array) config('modules.modules', []);
    }

    /**
     * Return all module keys (normalized).
     */
    public function keys(): array
    {
        return array_keys($this->all());
    }

    /**
     * Check if a module key exists.
     */
    public function has(string $key): bool
    {
        $key = $this->normalizeKey($key);
        $modules = $this->all();

        return isset($modules[$key]);
    }

    /**
     * Get module metadata by key.
     * Returns null if not found.
     */
    public function get(string $key): ?array
    {
        $key = $this->normalizeKey($key);
        $modules = $this->all();

        return $modules[$key] ?? null;
    }

    /**
     * Normalize module key to a consistent format.
     * Example: "UMS" => "ums", "  Core " => "core"
     */
    public function normalizeKey(string $key): string
    {
        return Str::of($key)->trim()->lower()->toString();
    }

    /**
     * Resolve a module base path to an absolute path.
     * Returns empty string if base_path is missing.
     */
    public function resolveBasePath(array $module): string
    {
        $relative = $module['base_path'] ?? '';
        $relative = trim((string) $relative, '/');

        return $relative !== '' ? base_path($relative) : '';
    }

    /**
     * Build a user-friendly list for console choice UI.
     * Output format: "Label (key)"
     */
    public function toChoiceList(): array
    {
        $modules = $this->all();

        $choices = [];
        foreach ($modules as $key => $module) {
            $label = $module['label'] ?? $module['key'] ?? $key;
            $choices[] = "{$label} ({$key})";
        }

        return $choices;
    }

    /**
     * Extract module key from a choice string formatted as: "Label (key)"
     * Returns normalized key or null on failure.
     */
    public function extractKeyFromChoice(string $picked): ?string
    {
        if (preg_match('/\(([^)]+)\)\s*$/', $picked, $m)) {
            return $this->normalizeKey($m[1]);
        }

        return null;
    }
}
