<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    const SUPPORTED = ['en', 'fr', 'es', 'ar'];

    const RTL = ['ar'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('locale', Cookie::get('locale', config('app.locale')));

        if (!in_array($locale, self::SUPPORTED)) {
            $locale = config('app.locale');
        }

        App::setLocale($locale);
        session(['locale' => $locale]);
        Cookie::queue('locale', $locale, 60 * 24 * 365);

        return $next($request);
    }
}
