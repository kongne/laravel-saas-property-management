<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CurrencySwitchController extends Controller
{
    public function switch($code)
    {
        $currency = Currency::where('code', $code)->where('is_active', true)->first();

        if (!$currency) {
            return redirect()->back();
        }

        Session::put('currency', $currency->code);

        return redirect()->back();
    }
}
