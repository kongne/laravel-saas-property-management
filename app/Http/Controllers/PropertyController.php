<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePropertyRequest;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::with('units');

        if (!Auth::user()->isAdmin()) {
            $query->where('user_id', Auth::id());
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('city', 'like', "%{$request->search}%")
                  ->orWhere('address', 'like', "%{$request->search}%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $properties = $query->latest()->paginate(10);
        return view('properties.index', compact('properties'));
    }

    public function create()
    {
        return view('properties.create');
    }

    public function store(StorePropertyRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('properties', 'public');
            }
            $data['images'] = $images;
        }

        Property::create($data);

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

        $property->update($data);

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
