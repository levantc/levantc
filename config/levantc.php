<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Locale Eloquent Model
    |--------------------------------------------------------------------------
    |
    | Fully qualified class name of the Locale model used by LocaleHelper.
    | Required only when using locale resolution helpers in a host application
    | that provides internationalization data (for example, a dedicated i18n module).
    |
    */
    'locale_model' => env('LEVANTC_LOCALE_MODEL', 'Levantc\\I18n\\Module\\Models\\Locale\\Locale'),

];
