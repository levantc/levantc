<?php

namespace Levantc\Traits\Console;

use Levantc\Traits\Modules\ModuleCatalog;

/**
 * Trait: ChoosesModule
 *
 * Purpose:
 * - Console-only module selection (interactive or via --module option).
 * - Delegates module data access to Core ModuleCatalog.
 *
 * Returns:
 * - [moduleKey, moduleMeta, moduleBasePath]
 */
trait ChoosesModule
{
    /**
     * Resolve the selected module using:
     * 1) Forced key param (highest priority)
     * 2) --module option (if defined on the command)
     * 3) Interactive choice() prompt (fallback)
     *
     * @param  string|null  $forcedKey  A forced module key (optional).
     * @param  string  $optionName  CLI option name to read from (default: "module").
     * @param  string  $question  Interactive prompt question.
     * @return array{0:string,1:array,2:string}
     */
    protected function chooseModule(
        ?string $forcedKey = null,
        string $optionName = 'module',
        string $question = 'Select module (where files will be generated)'
    ): array {
        /** @var ModuleCatalog $catalog */
        $catalog = app(ModuleCatalog::class);

        $modules = $catalog->all();

        if (empty($modules)) {
            $this->consoleError('No modules configured. Please define config/modules.php (modules key).');

            return ['', [], ''];
        }

        // Read CLI option if command supports option() and the option exists
        $optionKey = null;
        if (method_exists($this, 'option')) {
            $optionKey = $this->option($optionName); // may be null if option not defined
        }

        // Priority: forcedKey > --module option > interactive
        $selectedKey = $forcedKey ?: ($optionKey ?: null);

        // Non-interactive path
        if ($selectedKey) {
            $selectedKey = $catalog->normalizeKey((string) $selectedKey);

            if (! $catalog->has($selectedKey)) {
                $this->consoleError("Invalid module key: {$selectedKey}");
                $this->consoleMuted('Available modules: '.implode(', ', $catalog->keys()));

                return ['', [], ''];
            }

            $module = $catalog->get($selectedKey) ?? [];
            $basePath = $catalog->resolveBasePath($module);

            if ($basePath === '') {
                $this->consoleError("Module base_path is missing for: {$selectedKey}");

                return ['', [], ''];
            }

            return [$selectedKey, $module, $basePath];
        }

        // Interactive selection path
        $choices = $catalog->toChoiceList();

        $picked = $this->choice($question, $choices, 0);

        $selectedKey = $catalog->extractKeyFromChoice($picked);
        if (! $selectedKey || ! $catalog->has($selectedKey)) {
            $this->consoleError('Invalid module selection.');

            return ['', [], ''];
        }

        $module = $catalog->get($selectedKey) ?? [];
        $basePath = $catalog->resolveBasePath($module);

        if ($basePath === '') {
            $this->consoleError("Module base_path is missing for: {$selectedKey}");

            return ['', [], ''];
        }

        return [$selectedKey, $module, $basePath];
    }

    /**
     * Console error printer with graceful fallback.
     * If your command uses HasCliOutput trait, it will call printError().
     * Otherwise, it will fallback to Laravel's $this->error().
     */
    protected function consoleError(string $message): void
    {
        if (method_exists($this, 'printError')) {
            $this->printError($message);

            return;
        }

        // Fallback
        if (method_exists($this, 'error')) {
            $this->error($message);

            return;
        }

        // Last resort
        if (method_exists($this, 'line')) {
            $this->line($message);
        }
    }

    /**
     * Console muted printer with graceful fallback.
     */
    protected function consoleMuted(string $message): void
    {
        if (method_exists($this, 'printMuted')) {
            $this->printMuted($message);

            return;
        }

        // Fallback
        if (method_exists($this, 'line')) {
            $this->line($message);
        }
    }
}
