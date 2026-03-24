<?php

namespace Eta\Core\Module\Helpers;

use Eta\I18n\Module\Models\Locale\Locale;
use Carbon\Carbon;

class LocaleHelper
{
    /**
     * Cached locale ID to avoid repeated queries.
     *
     * @var array []|null
     */
    protected static array $localeIds = [];

    /**
     * Cached locale Code to avoid repeated queries.
     *
     * @var array []|null
     */
    protected static array $localeCodes = [];

    /**
     * Get the locale ID based on the current application locale.
     * Queries the `locales` table only once and caches the result.
     *
     * @param string|null $code
     * @return int
     */
    public static function getLocaleId(?string $code = null): int
    {
        $currentLocale = $code ?? app()->getLocale();
        if (!isset(self::$localeIds[$currentLocale])) {
            [$languageCode, $countryCode] = array_pad(
                explode('-', $currentLocale),
                2,
                null
            );
            $query = Locale::query()
                ->whereHas('language', function ($q) use ($languageCode) {
                    $q->where('code', $languageCode);
                });
            if ($countryCode) {
                $query->whereHas('country', function ($q) use ($countryCode) {
                    $q->where('code', $countryCode);
                });
            }
            self::$localeIds[$currentLocale] = $query->value('id');
        }
        return self::$localeIds[$currentLocale];
    }

    /**
     * Get the locale code (like "en" or "ar-SA") based on the locale ID.
     * Queries the `locales` table only once and caches the result.
     *
     * @param int|null $id
     * @return string|null
     */
    public static function getLocaleCode(?int $id = null): ?string
    {
        if ($id === null) {
            return app()->getLocale(); // fallback to current app locale
        }
        if (!isset(self::$localeCodes[$id])) {
            $locale = Locale::with(['language', 'country'])->find($id);

            if (!$locale) {
                self::$localeCodes[$id] = null;
            } else {
                $code = $locale->language->code; // eg. "en" or "ar"
                if ($locale->country) {
                    $code .= '-' . $locale->country->code; // eg. "ar-SA"
                }
                self::$localeCodes[$id] = $code;
            }
        }
        return self::$localeCodes[$id];
    }

    /**
     * Get the Locale model by its ID (cached).
     *
     * This returns the full Locale record (including relations if needed),
     * not just the locale code.
     *
     * @param int|null $id
     * @param bool $withRelations Whether to eager load language/country relations
     * @return Locale|null
     */
    public static function getLocaleById(?int $id, bool $withRelations = true): ?Locale
    {
        if ($id === null) {
            return null;
        }

        // Cache Locale models by ID to avoid repeated queries
        static $localeModels = [];

        if (!array_key_exists($id, $localeModels)) {
            $query = Locale::query();

            if ($withRelations) {
                $query->with(['language', 'country']);
            }

            $localeModels[$id] = $query->find($id);
        }

        return $localeModels[$id];
    }

    /**
     * Format a date based on the application's locale using Carbon only
     *
     * @param string $date ISO date string
     * @return string Localized formatted date
     */
    public static function formatLocalizedDate(string $date): string
    {
        $carbonDate = Carbon::parse($date);

        $locale = app()->getLocale();
        $carbonDate->locale($locale);
        if ($locale === 'ar') {
            return $carbonDate->translatedFormat('F Y');
        }
        return $carbonDate->format('F, Y');
    }

}
