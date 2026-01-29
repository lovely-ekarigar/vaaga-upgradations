<?php

namespace App\Http\Middleware;

use App\Models\Locale;
// TranslationManager removed - using Laravel's built-in translation
// use Barryvdh\TranslationManager\Manager;
// use Barryvdh\TranslationManager\Models\Translation;
use Closure;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

/**
 * Class LocaleMiddleware.
 */
class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function __construct()
    {
        // TranslationManager removed - get locales from filesystem instead
    }

    /**
     * Get available locales from language files
     *
     * @return array
     */
    protected function getLocales()
    {
        $locales = [];
        $langPath = resource_path('lang');
        if (File::exists($langPath)) {
            $directories = File::directories($langPath);
            foreach ($directories as $directory) {
                $locales[] = basename($directory);
            }
        }
        return $locales;
    }



    public function handle($request, Closure $next)
    {
        /*
         * Locale is enabled and allowed to be changed
         */
        if (config('locale.status') && Schema::hasTable('locales')) {
            $locales_list = $this->getLocales();
            if (!is_array($locales_list)) {
                $locales_list = [];
            }
            if (session()->has('locale') && in_array(session()->get('locale'), $locales_list)) {
                $locales = Locale::get();

                /*
                 * Set the Laravel locale
                 */
                app()->setLocale(session()->get('locale'));

                /*
                 * setLocale for php. Enables ->formatLocalized() with localized values for dates
                 */
                $idx = array_search(session()->get('locale'), $locales_list);
                if ($idx !== false) {
                    setlocale(LC_TIME, $locales_list[$idx]);
                    Carbon::setLocale($locales_list[$idx]);
                }

                /*
                 * Set the session variable for whether or not the app is using RTL support
                 */
                $locale_data = $locales->where('short_name', '=', session()->get('locale'))->first();
                if ($locale_data && isset($locale_data->display_type)) {
                    session(['display_type' => $locale_data->display_type === 'rtl' ? 'rtl' : 'ltr']);
                } else {
                    session(['display_type' => 'ltr']);
                }
            }
        }

        return $next($request);
    }
}
