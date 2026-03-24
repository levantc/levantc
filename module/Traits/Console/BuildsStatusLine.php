<?php

namespace Eta\Core\Module\Traits\Console;

use Symfony\Component\Console\Terminal;

/**
 * Trait: BuildsStatusLine
 *
 * Purpose:
 * - Produce aligned CLI output lines with a "stretching dash" filler.
 *
 * Format:
 * - <fileLabel> <----- dashes stretch -----> <Status>
 *
 * Coloring:
 * - Uses Symfony Console formatting tags.
 * - Supports independent coloring for:
 *   - fileLabel
 *   - dash filler
 *   - status text
 *
 * Special behavior (your requirement):
 * - Optionally make the fileLabel automatically use the SAME color/options as the status.
 */
trait BuildsStatusLine
{
    /**
     * Build a single line with stretch dashes.
     *
     * Parameters:
     * - $fileLabel  : left file label (e.g. migration file name).
     * - $statusText : right status label (e.g. Creating/Created).
     *
     * Status styling (right side):
     * - $statusFg, $statusBg, $statusOptions
     *
     * Dash styling (middle filler):
     * - $dashFg, $dashBg, $dashOptions
     *
     * File styling (left side):
     * - $fileFg, $fileBg, $fileOptions
     *
     * If $fileFollowsStatusStyle is true:
     * - fileFg/fileBg/fileOptions are ignored
     * - fileLabel uses the same style as status
     *
     * If $dashFollowsStatusStyle is true:
     * - dashFg/dashBg/dashOptions are ignored
     * - dash filler uses the same style as status
     *
     * Notes about width:
     * - Width calculations always use RAW (uncolored) strings.
     * - Tags do not consume visible terminal width.
     */
    protected function fileDashStatus(
        string $fileLabel,
        string $statusText,
        ?string $statusFg = null,
        ?string $statusBg = null,
        array $statusOptions = [],
        string $dashChar = '-',
        ?string $dashFg = 'gray',
        ?string $dashBg = null,
        array $dashOptions = [],
        ?string $fileFg = null,
        ?string $fileBg = null,
        array $fileOptions = [],
        bool $fileFollowsStatusStyle = true,
        bool $dashFollowsStatusStyle = false
    ): string {
        // 1) Detect terminal width (fallback to 80 if unknown).
        $terminal = new Terminal();
        $width = $terminal->getWidth() ?: 80;

        // 2) Normalize raw inputs.
        $fileRaw   = trim($fileLabel);
        $statusRaw = trim($statusText);

        // 3) Visible width calculator (better with Arabic/emoji when mb_strwidth exists).
        $displayWidth = function (string $s): int {
            return function_exists('mb_strwidth') ? mb_strwidth($s) : strlen($s);
        };

        // 4) Ensure dashChar is exactly 1 visible character.
        $dashChar = $this->firstCharOrDefault($dashChar, '-');

        // 5) Compute dash count based on raw widths.
        // Raw format:
        //   FILE + " " + DASHES + " " + STATUS
        $fixedWidth = $displayWidth($fileRaw) + 1 + 1 + $displayWidth($statusRaw);
        $dashCount  = max(3, $width - $fixedWidth);

        // 6) Decide file style.
        if ($fileFollowsStatusStyle) {
            $fileFg      = $statusFg;
            $fileBg      = $statusBg;
            $fileOptions = $statusOptions;
        }

        // 7) Decide dash style.
        if ($dashFollowsStatusStyle) {
            $dashFg      = $statusFg;
            $dashBg      = $statusBg;
            $dashOptions = $statusOptions;
        }

        // 8) Build raw segments.
        $dashRaw = str_repeat($dashChar, $dashCount);

        // 9) Apply styling per segment.
        $fileSegment   = $this->wrapConsoleStyle($fileRaw, $fileFg, $fileBg, $fileOptions);
        $dashSegment   = $this->wrapConsoleStyle($dashRaw, $dashFg, $dashBg, $dashOptions);
        $statusSegment = $this->wrapConsoleStyle($statusRaw, $statusFg, $statusBg, $statusOptions);

        // 10) Compose final line:
        // FILE + space + DASHES + space + STATUS
        return $fileSegment . ' ' . $dashSegment . ' ' . $statusSegment;
    }

    /**
     * Wrap a text with Symfony console style tags.
     */
    protected function wrapConsoleStyle(string $text, ?string $fg, ?string $bg, array $options): string
    {
        $fg = $fg !== null ? trim($fg) : null;
        $bg = $bg !== null ? trim($bg) : null;

        $options = array_values(
            array_filter(
                array_map('trim', $options),
                fn ($v) => $v !== ''
            )
        );

        if (($fg === null || $fg === '') && ($bg === null || $bg === '') && empty($options)) {
            return $text;
        }

        $parts = [];

        if ($fg !== null && $fg !== '') {
            $parts[] = "fg={$fg}";
        }

        if ($bg !== null && $bg !== '') {
            $parts[] = "bg={$bg}";
        }

        if (!empty($options)) {
            $parts[] = "options=" . implode(',', $options);
        }

        $style = implode(';', $parts);

        return "<{$style}>{$text}</>";
    }

    /**
     * Return the first character of $value, or $default if $value is empty.
     */
    protected function firstCharOrDefault(string $value, string $default): string
    {
        $value = trim($value);

        if ($value === '') {
            return $default;
        }

        if (function_exists('mb_substr')) {
            return mb_substr($value, 0, 1);
        }

        return substr($value, 0, 1);
    }
}
