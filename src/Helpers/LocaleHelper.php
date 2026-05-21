<?php

namespace Levantc\Helpers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use RuntimeException;

class LocaleHelper
{
    /**
     * Cached locale ID to avoid repeated queries.
     *
     * @var array<string, int|null>
     */
    protected static array $localeIds = [];

    /**
     * Cached locale Code to avoid repeated queries.
     *
     * @var array<int, string|null>
     */
    protected static array $localeCodes = [];

    /**
     * Get the locale ID based on the current application locale.
     * Queries the locales table only once and caches the result.
     */
    public static function getLocaleId(?string $code = null): int
    {
        $currentLocale = $code ?? app()->getLocale();
        if (! isset(self::$localeIds[$currentLocale])) {
            [$languageCode, $countryCode] = array_pad(
                explode('-', $currentLocale),
                2,
                null
            );
            $query = self::localeQuery()
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

        return (int) self::$localeIds[$currentLocale];
    }

    /**
     * Get the locale code (like "en" or "ar-SA") based on the locale ID.
     */
    public static function getLocaleCode(?int $id = null): ?string
    {
        if ($id === null) {
            return app()->getLocale();
        }
        if (! isset(self::$localeCodes[$id])) {
            $locale = self::localeQuery()->with(['language', 'country'])->find($id);

            if (! $locale) {
                self::$localeCodes[$id] = null;
            } else {
                $code = $locale->language->code;
                if ($locale->country) {
                    $code .= '-'.$locale->country->code;
                }
                self::$localeCodes[$id] = $code;
            }
        }

        return self::$localeCodes[$id];
    }

    /**
     * Get the Locale model by its ID (cached).
     */
    public static function getLocaleById(?int $id, bool $withRelations = true): ?Model
    {
        if ($id === null) {
            return null;
        }

        static $localeModels = [];

        if (! array_key_exists($id, $localeModels)) {
            $query = self::localeQuery();

            if ($withRelations) {
                $query->with(['language', 'country']);
            }

            $localeModels[$id] = $query->find($id);
        }

        return $localeModels[$id];
    }

    /**
     * Format a date based on the application's locale using Carbon only.
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

    /**
     * @return Builder<Model>
     */
    protected static function localeQuery(): Builder
    {
        $model = config('levantc.locale_model');

        if (! is_string($model) || $model === '' || ! is_subclass_of($model, Model::class)) {
            throw new RuntimeException(
                'LocaleHelper requires config("levantc.locale_model") to be a valid Eloquent model class.'
            );
        }

        return $model::query();
    }
}
