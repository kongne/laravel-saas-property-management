<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnitApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Unit::with('property', 'tenants.user');
        $user = Auth::user();

        if ($request->property_id) {
            $query->where('property_id', $request->property_id);
        }

        if (!$user->isAdmin()) {
            $query->whereHas('property', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        return response()->json($query->latest()->paginate($request->per_page ?? 15));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'unit_number' => 'required|string|max:50',
            'floor' => 'nullable|integer',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'square_feet' => 'nullable|numeric|min:0',
            'rent_amount' => 'required|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:available,occupied,maintenance',
            'description' => 'nullable|string',
        ]);

        $property = \App\Models\Property::findOrFail($request->property_id);
        if (!Auth::user()->isAdmin() && $property->user_id !== Auth::id()) {
            abort(403);
        }

        $unit = Unit::create($data);
        return response()->json(['data' => $unit], 201);
    }

    public function show(Unit $unit)
    {
        if (!Auth::user()->isAdmin()) {
            $unit->load('property');
            if ($unit->property->user_id !== Auth::id()) {
                abort(403);
            }
        }
        $unit->load('property', 'tenants.user', 'activeTenant.user', 'payments', 'maintenanceRequests');
        return response()->json(['data' => $unit]);
    }

    public function update(Request $request, Unit $unit)
    {
        $unit->load('property');
        if (!Auth::user()->isAdmin() && $unit->property->user_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'property_id' => 'sometimes|exists:properties,id',
            'unit_number' => 'sometimes|string|max:50',
            'floor' => 'nullable|integer',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'square_feet' => 'nullable|numeric|min:0',
            'rent_amount' => 'sometimes|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:available,occupied,maintenance',
            'description' => 'nullable|string',
        ]);

        $unit->update($data);
        return response()->json(['data' => $unit]);
    }

    public function destroy(Unit $unit)
    {
        $unit->load('property');
        if (!Auth::user()->isAdmin() && $unit->property->user_id !== Auth::id()) {
            abort(403);
        }

        if ($unit->tenants()->exists()) {
            return response()->json(['message' => 'Cannot delete unit with existing tenants.'], 422);
        }
        $unit->delete();
        return response()->json(null, 204);
    }
}
