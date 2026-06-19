<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePropertyRequest;
use App\Models\ActivityLog;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::withCount('units');

        if (!Auth::user()->isAdmin()) {
            $query->where('user_id', Auth::id());
        }

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('district', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->city) {
            $query->where('city', $request->city);
        }

        if ($request->featured) {
            $query->where('featured', true);
        }

        $sortField = $request->sort ?? 'created_at';
        $sortDir = $request->direction ?? 'desc';
        $query->orderBy($sortField, $sortDir);

        $properties = $query->paginate(10)->withQueryString();
        return view('properties.index', compact('properties'));
    }

    public function create()
    {
        return view('properties.create');
    }

    public function store(StorePropertyRequest $request)
    {
        if (!Auth::user()->isAdmin() && Auth::user()->hasReachedLimit('properties')) {
            return back()->withInput()->with('error', 'You have reached your plan limit for properties. Please upgrade to add more.');
        }

        $data = $request->validated();
        $data['user_id'] = Auth::id();

        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('properties', 'public');
            }
            $data['images'] = $images;
        }

        if ($request->hasFile('documents')) {
            $docs = [];
            foreach ($request->file('documents') as $doc) {
                $docs[] = $doc->store('properties/documents', 'public');
            }
            $data['documents'] = $docs;
        }

        $data['amenities'] = $request->amenities ?? [];
        $data['nearby_places'] = $request->nearby_places ? json_decode($request->nearby_places, true) : [];
        $data['featured'] = $request->has('featured');

        Property::create($data);
        ActivityLog::log(Auth::user(), 'property_created', "Created property: {$data['name']}");

        return redirect()->route('properties.index')
            ->with('success', 'Property created successfully.');
    }

    public function show(Property $property)
    {
        $this->authorizeAccess($property);
        $property->load(['units' => function ($q) {
            $q->with('activeTenant.user');
        }]);

        return view('properties.show', compact('property'));
    }

    public function edit(Property $property)
    {
        $this->authorizeAccess($property);
        return view('properties.edit', compact('property'));
    }

    public function update(StorePropertyRequest $request, Property $property)
    {
        $this->authorizeAccess($property);
        $data = $request->validated();

        if ($request->hasFile('images')) {
            if ($property->images) {
                foreach ($property->images as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('properties', 'public');
            }
            $data['images'] = $images;
        }

        if ($request->hasFile('documents')) {
            if ($property->documents) {
                foreach ($property->documents as $oldDoc) {
                    Storage::disk('public')->delete($oldDoc);
                }
            }
            $docs = [];
            foreach ($request->file('documents') as $doc) {
                $docs[] = $doc->store('properties/documents', 'public');
            }
            $data['documents'] = $docs;
        }

        $data['amenities'] = $request->amenities ?? [];
        $data['nearby_places'] = $request->nearby_places ? json_decode($request->nearby_places, true) : [];
        $data['featured'] = $request->has('featured');

        $property->update($data);
        ActivityLog::log(Auth::user(), 'property_updated', "Updated property: {$property->name}");

        return redirect()->route('properties.index')
            ->with('success', 'Property updated successfully.');
    }

    public function destroy(Property $property)
    {
        $this->authorizeAccess($property);

        if ($property->units()->exists()) {
            return back()->with('error', 'Cannot delete property with existing units.');
        }

        if ($property->images) {
            foreach ($property->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $property->delete();
        ActivityLog::log(Auth::user(), 'property_deleted', "Deleted property: {$property->name}");

        return redirect()->route('properties.index')
            ->with('success', 'Property deleted successfully.');
    }

    private function authorizeAccess(Property $property): void
    {
        if (!Auth::user()->isAdmin() && $property->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
