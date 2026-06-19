<?php

namespace App\Http\Controllers;

use App\Http\Middleware\SetLocale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;

class LocaleController extends Controller
{
    public function switch($locale)
    {
        if (!in_array($locale, SetLocale::SUPPORTED)) {
            $locale = config('app.locale');
        }

        session(['locale' => $locale]);
        App::setLocale($locale);
        Cookie::queue('locale', $locale, 60 * 24 * 365);

        return redirect()->back();
    }
}
