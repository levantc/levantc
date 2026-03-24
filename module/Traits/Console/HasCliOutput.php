<?php

namespace Eta\Core\Module\Traits\Console;

/**
 * Trait: HasCliOutput
 *
 * Purpose:
 * - Provide a unified, consistent output API for CLI commands.
 * - Prevent copy/paste of repetitive console UI code across 100+ commands.
 * - Centralize message styling (colors/icons/options) in ONE place.
 *
 * How it works:
 * - This trait prints messages using $this->line() (available in Laravel Command).
 * - It uses Symfony Console formatting tags via wrapConsoleStyle()
 *   to colorize the output consistently.
 *
 * Requirements (Host class must provide):
 * - $this->line(string $message): provided by Illuminate\Console\Command.
 * - wrapConsoleStyle(...): provided by BuildsStatusLine trait.
 * - fileDashStatus(...): provided by BuildsStatusLine trait.
 *
 * Recommended usage:
 * - In your Command class:
 *   use BuildsStatusLine, HasCliOutput;
 */
trait HasCliOutput
{

    /**
     * Style presets (single source of truth).
     *
     * Why this exists:
     * - You can change colors/icons/options once here and all commands update instantly.
     * - Avoids inconsistent coloring between commands.
     *
     * Notes:
     * - Colors are Symfony Console-compatible color names (e.g. green, yellow, red, cyan, white, gray).
     * - Options are Symfony Console options (e.g. bold, underscore).
     */
    protected array $cliStyles = [
        'title_fg'   => 'green',
        'title_opts' => ['bold'],

        'info_fg'    => 'cyan',
        'info_opts'  => [],

        'success_fg' => 'green',
        'success_opts' => [],

        'warning_fg' => 'yellow',
        'warning_opts' => ['bold'],

        'error_fg'   => 'red',
        'error_opts' => ['bold'],

        'muted_fg'   => 'gray',
        'muted_opts' => [],
    ];

    // -------------------------------------------------------------------------
    // Message printers (High-level API)
    // -------------------------------------------------------------------------
    //
    // These helpers are intentionally simple:
    // - Each one prints a single line.
    // - Each one uses a predefined style preset.
    // - Each one can be used everywhere in your commands with zero duplication.
    //
    // If you want a message with fully custom style, use printStyled().
    // -------------------------------------------------------------------------

    /**
     * Print a title line.
     *
     * Intended use:
     * - Section headers, major steps, or command-level headings.
     *
     * Example:
     * $this->printTitle('Generating migrations');
     */
    protected function printTitle(string $text): void
    {
        $this->line(
            $this->wrapConsoleStyle($text, $this->cliStyles['title_fg'], null, $this->cliStyles['title_opts'])
        );
    }

    /**
     * Print an informational line.
     *
     * Intended use:
     * - Non-critical messages that explain what is happening.
     * - Examples: selected module, resolved paths, progress hints.
     */
    protected function printInfo(string $text): void
    {
        $this->line(
            $this->wrapConsoleStyle("{$text}", $this->cliStyles['info_fg'], null, $this->cliStyles['info_opts'])
        );
    }

    /**
     * Print a success line.
     *
     * Intended use:
     * - Success confirmations (Created, Completed, Done).
     */
    protected function printSuccess(string $text): void
    {
        $this->line(
            $this->wrapConsoleStyle("{$text}", $this->cliStyles['success_fg'], null, $this->cliStyles['success_opts'])
        );
    }

    /**
     * Print a warning line.
     *
     * Intended use:
     * - Non-fatal issues (Skipped, missing optional fields, fallback behavior).
     */
    protected function printWarning(string $text): void
    {
        $this->line(
            $this->wrapConsoleStyle("{$text}", $this->cliStyles['warning_fg'], null, $this->cliStyles['warning_opts'])
        );
    }

    /**
     * Print an error line.
     *
     * Intended use:
     * - Fatal or near-fatal errors (cannot continue).
     * - Examples: missing stubs folder, cannot write file, invalid module selection.
     */
    protected function printError(string $text): void
    {
        $this->line(
            $this->wrapConsoleStyle("{$text}", $this->cliStyles['error_fg'], null, $this->cliStyles['error_opts'])
        );
    }

    /**
     * Print a muted / secondary line.
     *
     * Intended use:
     * - Additional details below another main message.
     * - Examples: hints, paths, tips, extra context that should not be visually dominant.
     */
    protected function printMuted(string $text): void
    {
        $this->line(
            $this->wrapConsoleStyle($text, $this->cliStyles['muted_fg'], null, $this->cliStyles['muted_opts'])
        );
    }

    // -------------------------------------------------------------------------
    // Low-level / advanced printers
    // -------------------------------------------------------------------------

    /**
     * Print a fully styled line (custom colors/options).
     *
     * Use this when:
     * - Presets are not enough.
     * - You want a special color or combination for a specific case.
     *
     * Parameters:
     * - $text    : the message text
     * - $fg      : foreground color (optional)
     * - $bg      : background color (optional)
     * - $options : e.g. ['bold', 'underscore']
     */
    protected function printStyled(string $text, ?string $fg = null, ?string $bg = null, array $options = []): void
    {
        $this->line($this->wrapConsoleStyle($text, $fg, $bg, $options));
    }

    // -------------------------------------------------------------------------
    // Full-width "file ---- status" printer
    // -------------------------------------------------------------------------

    /**
     * Print a full-width "file ---- status" line.
     *
     * This is used when you want aligned output per generated file (or per action).
     *
     * Example output:
     * 2026_02_20_123456_create_users_table.php -------------------------- <fg=yellow>Creating</>
     *
     * Why:
     * - When generating many files, aligned lines are easier to scan quickly.
     *
     * Parameters:
     * - $fileLabel  : left side label (usually the file name)
     * - $statusText : right side status label (provided by caller)
     * - $fg/$bg/$options: style ONLY for the status text (caller-controlled)
     * - $dashChar   : dash character used for the filler (default "-")
     */
    protected function printStatusLine(
        string $fileLabel,
        string $statusText,
        ?string $fg = null,
        ?string $bg = null,
        array $options = [],
        string $dashChar = '-'
    ): void {
        $this->line(
            $this->fileDashStatus($fileLabel, $statusText, $fg, $bg, $options, $dashChar)
        );
    }
}
