<?php

namespace Eta\Core\Module\DTOs;

/**
 * Base Data Transfer Object.
 *
 * Provides a common contract for building DTOs from arrays.
 * All DTOs should extend this class.
 */
abstract class TranslationDTO extends DTO
{
    /**
     * Determine if null values should be filtered out before saving.
     */
    public function shouldFilterNulls(): bool
    {
        return false;
    }
}
