<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function districts(Request $request)
    {
        $city = $request->city;
        if (!$city) {
            return response()->json([]);
        }
        return response()->json(Property::districtsForCity($city));
    }
}
