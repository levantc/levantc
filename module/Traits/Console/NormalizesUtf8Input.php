<?php

namespace Sadeem\Core\Module\Traits\Console;

/**
 * Trait NormalizesUtf8Input
 *
 * Purpose:
 * - Solve the "????" issue when entering Arabic text in Windows terminals
 *   (PowerShell / CMD) during interactive CLI prompts.
 *
 * Root cause (typical):
 * - The terminal may provide input in a Windows code page (e.g. CP1256 / Windows-1256)
 *   while the PHP process writes it to files assuming UTF-8.
 * - Result: Arabic characters become "????" in generated files.
 *
 * What this trait does:
 * - Takes any user input string and tries to normalize it into UTF-8 safely.
 * - Provides convenient wrappers around ask() and anticipate() that ALWAYS return UTF-8.
 *
 * Usage:
 * - In your Command class:
 *     use NormalizesUtf8Input;
 *
 * - Then replace:
 *     $value = $this->ask('Question');
 *   with:
 *     $value = $this->askUtf8('Question');
 *
 * Extra tip (important):
 * - Even if input is fixed, your stub files themselves must be saved in UTF-8.
 * - If stubs are saved in a different encoding, output may still look broken.
 */
trait NormalizesUtf8Input
{
    /**
     * Normalize any string into UTF-8.
     *
     * Strategy:
     * 1) If already valid UTF-8 -> return as-is.
     * 2) Otherwise attempt detection (mb_detect_encoding) among likely encodings.
     * 3) Fallback to iconv conversions for Arabic-friendly Windows code pages.
     * 4) If everything fails, return original value (best effort).
     *
     * Safety:
     * - We strip null bytes.
     * - We use //IGNORE on iconv to avoid fatal conversion errors.
     */
    protected function normalizeUtf8(?string $value): string
    {
        // Convert null to empty string to simplify logic.
        $value = (string) ($value ?? '');

        // Remove null bytes that can appear from some odd terminal behaviors.
        $value = str_replace("\0", '', $value);

        /**
         * FAST PATH:
         * If mb_check_encoding exists and the input is already UTF-8,
         * return it immediately.
         */
        if (function_exists('mb_check_encoding') && mb_check_encoding($value, 'UTF-8')) {
            return $value;
        }

        /**
         * TRY #1: mbstring detection + conversion
         *
         * We try a set of encodings that commonly appear on Windows/Arabic terminals:
         * - UTF-8: already good but maybe mb_check_encoding wasn't available
         * - Windows-1256 / CP1256: common Arabic Windows code pages
         * - ISO-8859-6: Arabic ISO encoding (less common but possible)
         * - Windows-1252 / ISO-8859-1: sometimes terminals leak these for punctuation
         * - ASCII: fallback
         */
        if (function_exists('mb_detect_encoding') && function_exists('mb_convert_encoding')) {
            $detected = mb_detect_encoding(
                $value,
                ['UTF-8', 'Windows-1256', 'CP1256', 'ISO-8859-6', 'Windows-1252', 'ISO-8859-1', 'ASCII'],
                true
            );

            if ($detected) {
                $converted = mb_convert_encoding($value, 'UTF-8', $detected);

                // If conversion yielded something meaningful, return it.
                if ($converted !== '') {
                    return $converted;
                }
            }
        }

        /**
         * TRY #2: iconv fallback
         *
         * iconv is often available on Windows builds of PHP.
         * We try the most likely source encodings and convert to UTF-8.
         */
        if (function_exists('iconv')) {
            foreach (['Windows-1256', 'CP1256', 'ISO-8859-6', 'Windows-1252', 'ISO-8859-1'] as $from) {
                $try = @iconv($from, 'UTF-8//IGNORE', $value);

                if ($try !== false && $try !== '') {
                    return $try;
                }
            }
        }

        /**
         * LAST RESORT:
         * We couldn't confidently convert. Return original.
         * (At least we didn't crash the command.)
         */
        return $value;
    }

    /**
     * Wrapper around Laravel's ask().
     *
     * Why:
     * - Forces UTF-8 normalization for any interactive input.
     *
     * Example:
     *   $arabicSingular = $this->askUtf8('Arabic singular name (Required)');
     */
    protected function askUtf8(string $question, ?string $default = null): string
    {
        // Laravel ask($question, $default = null)
        $answer = $this->ask($question, $default);

        // Normalize immediately.
        return $this->normalizeUtf8(is_string($answer) ? $answer : (string) $answer);
    }

    /**
     * Wrapper around Laravel's anticipate().
     *
     * Why:
     * - Some commands use anticipate() for better UX (autocomplete-like behavior).
     * - We still need to normalize output for Windows terminals.
     *
     * Example:
     *   $value = $this->anticipateUtf8('Type value', ['one', 'two']);
     */
    protected function anticipateUtf8(string $question, array $choices, ?string $default = null): string
    {
        // Laravel anticipate($question, $choices, $default = null)
        $answer = $this->anticipate($question, $choices, $default);

        return $this->normalizeUtf8(is_string($answer) ? $answer : (string) $answer);
    }

    /**
     * Optional helper:
     * Ask a required question (keeps asking until non-empty) BUT with UTF-8 normalization.
     *
     * This is built to fit your existing askRequired pattern.
     */
    protected function askRequiredUtf8(string $question, string $default = ''): string
    {
        while (true) {
            $value = $this->askUtf8($question, $default);
            $value = trim($value);

            if ($value !== '') {
                return $value;
            }

            // Keep your UX consistent.
            if (method_exists($this, 'printWarning')) {
                $this->printWarning('This field is required. Please enter a value.');
            } else {
                $this->warn('This field is required. Please enter a value.');
            }
        }
    }
}
