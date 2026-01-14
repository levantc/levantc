<?php

namespace Sadeem\Core\Module\Helpers;

use Sadeem\I18n\Module\Models\Locale;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

class LocaleHelper
{
    /**
     * Cached locale ID to avoid repeated queries.
     *
     * @var array []|null
     */
    protected static array $localeIds = [];

    /**
     * Get the locale ID based on the current application locale.
     * Queries the `locales` table only once and caches the result.
     *
     * @return int|null
     */
    public static function getLocaleId(): ?int
    {
        $currentLocale = App::getLocale();

        if (!isset(self::$localeIds[$currentLocale])) {
            $locale = Locale::where('code', $currentLocale)->first();
            self::$localeIds[$currentLocale] = $locale?->id;
        }

        return self::$localeIds[$currentLocale];
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
