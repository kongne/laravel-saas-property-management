<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Booking;
use App\Notifications\BookingNotification;
use App\Notifications\VisitorBookingNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

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

        if ($request->purpose) {
            $query->where('purpose', $request->purpose);
        }

        if ($request->category) {
            $query->where('category', $request->category);
        }

        if ($request->bedrooms) {
            $query->where('bedrooms', $request->bedrooms);
        }

        if ($request->region) {
            $query->where('region', $request->region);
        }

        if ($request->area) {
            $query->where('area', $request->area);
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
            })->orWhere('price', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $query->whereHas('units', function ($q) use ($request) {
                $q->where('rent_amount', '<=', $request->max_price);
            })->orWhere('price', '<=', $request->max_price);
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
        $categories = Property::categories();
        $purposes = [
            'rent' => 'For Rent',
            'sale' => 'For Sale',
        ];

        return view('public.properties', compact('properties', 'cities', 'types', 'amenityOptions', 'categories', 'purposes'));
    }

    public function show(Property $property)
    {
        abort_unless($property->status === 'active', 404);
        $property->load(['units' => function ($q) {
            $q->with('activeTenant.user')->where('status', 'available');
        }]);
        return view('public.property-show', compact('property'));
    }

    public function bookVisit(Request $request, Property $property)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'visit_date' => 'required|date|after_or_equal:today',
            'visit_time' => 'required|string|max:50',
            'message' => 'nullable|string',
            'unit_id' => 'nullable|exists:units,id',
        ]);

        $booking = new Booking($validated);
        $booking->property_id = $property->id;
        $booking->status = 'pending';
        $booking->save();

        // Notify landlord
        $landlord = $property->user;
        if ($landlord) {
            $landlord->notify(new BookingNotification($booking));
        }

        // Notify visitor
        Notification::route('mail', $booking->email)
            ->notify(new VisitorBookingNotification($booking));

        return back()->with('success', 'Your visit booking request has been submitted successfully! The agent will contact you shortly.');
    }
}
