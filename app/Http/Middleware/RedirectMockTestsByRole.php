<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Redirect non-admins from /user/mocktests to the correct page so they never get 401.
 * Only administrators reach the mocktest admin controller; students/teachers/others are redirected.
 */
class RedirectMockTestsByRole
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        // Only administrators go through to the admin mocktest controller
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Everyone else: redirect by role so the controller is never hit (no 401)
        if ($user->hasRole('student')) {
            return redirect()->route('student.mocktests.dashboard');
        }

        if ($user->hasRole('teacher')) {
            return redirect()->route('tutor.mocktests.available');
        }

        // Author or any other role: send to dashboard
        return redirect()->route('admin.dashboard');
    }
}
