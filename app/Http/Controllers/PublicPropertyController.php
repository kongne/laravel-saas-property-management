<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PublicPropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::active()->withCount('units');

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('district', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->city) {
            $query->where('city', $request->city);
        }

        if ($request->district) {
            $query->where('district', $request->district);
        }

        if ($request->min_price) {
            $query->whereHas('units', function ($q) use ($request) {
                $q->where('rent_amount', '>=', $request->min_price);
            });
        }

        if ($request->max_price) {
            $query->whereHas('units', function ($q) use ($request) {
                $q->where('rent_amount', '<=', $request->max_price);
            });
        }

        if ($request->featured) {
            $query->where('featured', true);
        }

        if ($request->amenities) {
            $amenities = (array) $request->amenities;
            foreach ($amenities as $amenity) {
                $query->whereJsonContains('amenities', $amenity);
            }
        }

        $sortField = $request->sort ?? 'created_at';
        $sortDir = $request->direction ?? 'desc';
        $query->orderBy($sortField, $sortDir);

        $properties = $query->paginate(12)->withQueryString();

        $cities = Property::cities();
        $types = Property::propertyTypes();
        $amenityOptions = Property::amenityOptions();

        return view('public.properties', compact('properties', 'cities', 'types', 'amenityOptions'));
    }

    public function show(Property $property)
    {
        abort_unless($property->status === 'active', 404);
        $property->load(['units' => function ($q) {
            $q->with('activeTenant.user')->where('status', 'available');
        }]);
        return view('public.property-show', compact('property'));
    }
}
