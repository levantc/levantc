<?php

namespace Sadeem\Core\Module\Console;

use Illuminate\Console\Command as LaravelCommand;
use Sadeem\Core\Module\Traits\Console\BuildsStatusLine;
use Sadeem\Core\Module\Traits\Console\ChoosesModule;
use Sadeem\Core\Module\Traits\Console\HasCliOutput;
use Sadeem\Core\Module\Traits\Console\NormalizesUtf8Input;
use Sadeem\Core\Module\Traits\Stubs\BuildsModelTokens;
use Sadeem\Core\Module\Traits\Stubs\BuildsModuleTokens;

/**
 * Abstract Class: Command (Base CLI Command)
 *
 * Purpose:
 * - This is the single base class for ALL custom CLI commands in the system.
 * - Every command must extend this class instead of extending LaravelCommand directly.
 *
 * Why it exists:
 * - Centralizes shared CLI "infrastructure" to avoid duplication across 100+ commands.
 * - Enforces a unified CLI UX (consistent output formatting, colors, statuses, module selection).
 * - Makes global CLI improvements possible by changing one place.
 *
 * What it provides (via Traits):
 *
 * 1) ChoosesModule
 *    - Unified module resolution strategy:
 *      - Uses --module option when provided
 *      - Otherwise prompts user with an interactive module picker
 *    - Returns a consistent tuple:
 *      [moduleKey, moduleMeta, moduleBasePath]
 *
 * 2) BuildsModelTokens
 *    - Normalizes model names (StudlyCase).
 *    - Builds a standard token map for model naming variations:
 *      camelCase, snake_case, kebab-case, plural forms, upper/lower variants, etc.
 *    - Provides replaceTokens() to apply token replacement on:
 *      - file paths (folders + filenames)
 *      - file contents
 *
 * 3) BuildsStatusLine
 *    - Generates aligned, terminal-width aware status lines:
 *      "<file> ----(dashes)---- <status>"
 *    - Supports separate styling for:
 *      - file label
 *      - dash filler
 *      - status label
 *    - Can optionally sync the filename color with the status color.
 *
 * 4) HasCliOutput
 *    - High-level output helpers for consistent messaging:
 *      printTitle(), printInfo(), printSuccess(), printWarning(), printError(), printMuted()
 *    - Standardizes how command output looks across the entire CLI.
 *
 * 5) NormalizesUtf8Input
 *    - Ensures interactive terminal input (especially Arabic in Windows shells) is normalized to UTF-8.
 *    - Prevents common "????" corruption when reading user input and writing generated files.
 *
 * Important Notes:
 * - This base command intentionally contains NO business logic.
 * - It only provides shared utilities, conventions, and CLI infrastructure.
 */
abstract class Command extends LaravelCommand
{
    use ChoosesModule, BuildsModelTokens, BuildsModuleTokens, BuildsStatusLine, HasCliOutput, NormalizesUtf8Input;
}
