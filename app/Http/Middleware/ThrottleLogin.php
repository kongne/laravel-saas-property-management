<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ThrottleLogin
{
    public function __construct(
        protected RateLimiter $limiter,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $key = Str::lower($request->input('email')) . '|' . $request->ip();

        if ($this->limiter->tooManyAttempts($key, 5)) {
            $seconds = $this->limiter->availableIn($key);
            return back()->withErrors([
                'email' => trans('auth.throttle', ['seconds' => $seconds, 'minutes' => ceil($seconds / 60)]),
            ])->onlyInput('email');
        }

        $response = $next($request);

        if ($response->getStatusCode() === 302 && session()->has('errors')) {
            $this->limiter->hit($key, 60);
        }

        return $response;
    }
}
