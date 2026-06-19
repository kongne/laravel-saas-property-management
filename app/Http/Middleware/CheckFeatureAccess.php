<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckFeatureAccess
{
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        if (!Auth::user()->canAccessFeature($feature)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Your current plan does not include this feature.'], 403);
            }

            return redirect()->route('billing.index')
                ->with('error', 'Your current plan does not include this feature. Please upgrade to access it.');
        }

        return $next($request);
    }
}
