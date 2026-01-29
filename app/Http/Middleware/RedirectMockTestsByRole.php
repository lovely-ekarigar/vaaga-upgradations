<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Redirect students and teachers from admin mocktest routes to their correct dashboards.
 * Prevents 401 when they hit /user/mocktests (admin index) instead of /user/student/mocktests or /user/tutor/mocktests.
 */
class RedirectMockTestsByRole
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return $next($request);
        }

        if ($request->user()->hasRole('student')) {
            return redirect()->route('student.mocktests.dashboard');
        }

        if ($request->user()->hasRole('teacher')) {
            return redirect()->route('tutor.mocktests.available');
        }

        return $next($request);
    }
}
