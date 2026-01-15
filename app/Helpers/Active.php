<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Request;

/**
 * Active helper class to replace hieu-le/active package
 * Compatible with Laravel 10
 */
class Active
{
    /**
     * Check if the URI matches one of the specific patterns
     *
     * @param array|string $patterns
     * @return bool
     */
    public static function checkUriPattern($patterns)
    {
        $currentUri = Request::path();
        
        if (!is_array($patterns)) {
            $patterns = [$patterns];
        }
        
        foreach ($patterns as $pattern) {
            // Handle wildcard patterns
            if (str_contains($pattern, '*')) {
                $pattern = str_replace('*', '.*', $pattern);
                $pattern = '#^' . $pattern . '$#';
                if (preg_match($pattern, $currentUri)) {
                    return true;
                }
            } elseif ($currentUri === $pattern || str_starts_with($currentUri, $pattern . '/')) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Check if the current route matches the given route name
     *
     * @param string $route
     * @return bool
     */
    public static function checkRoute($route)
    {
        return Request::routeIs($route);
    }
    
    /**
     * Check if the URI matches one of the specific URIs
     *
     * @param array|string $uris
     * @return bool
     */
    public static function checkUri($uris)
    {
        $currentUri = Request::path();
        
        if (!is_array($uris)) {
            $uris = [$uris];
        }
        
        return in_array($currentUri, $uris);
    }
}
