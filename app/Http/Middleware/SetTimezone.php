<?php

namespace App\Http\Middleware;

use Closure;
use DateTimeZone;

class SetTimezone
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $timezone = $request->cookie('tz');

        if ($timezone && in_array($timezone, DateTimeZone::listIdentifiers(), true)) {
            config(['app.timezone' => $timezone]);
            date_default_timezone_set($timezone);
        }

        return $next($request);
    }
}
