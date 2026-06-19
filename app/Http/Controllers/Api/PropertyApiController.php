<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePropertyRequest;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PropertyApiController extends Controller
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

        return response()->json($query->latest()->paginate($request->per_page ?? 15));
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

        $property = Property::create($data);
        return response()->json(['data' => $property], 201);
    }

    public function show(Property $property)
    {
        $this->authorizeAccess($property);
        $property->load('units.activeTenant.user');
        return response()->json(['data' => $property]);
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
        return response()->json(['data' => $property]);
    }

    public function destroy(Property $property)
    {
        $this->authorizeAccess($property);
        if ($property->units()->exists()) {
            return response()->json(['message' => 'Cannot delete property with existing units.'], 422);
        }
        if ($property->images) {
            foreach ($property->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        $property->delete();
        return response()->json(null, 204);
    }

    private function authorizeAccess(Property $property): void
    {
        if (!Auth::user()->isAdmin() && $property->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
