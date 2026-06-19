<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Tenant::with('unit.property', 'user');
        $user = Auth::user();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->search}%")
                  ->orWhere('last_name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if (!$user->isAdmin()) {
            $query->whereHas('unit.property', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        return response()->json($query->latest()->paginate($request->per_page ?? 15));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:tenants,email',
            'phone' => 'nullable|string|max:20',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'id_number' => 'nullable|string|max:50',
        ]);

        $unit = \App\Models\Unit::with('property')->findOrFail($request->unit_id);
        if (!Auth::user()->isAdmin() && $unit->property->user_id !== Auth::id()) {
            abort(403);
        }

        $data['status'] = 'active';
        $tenant = Tenant::create($data);
        return response()->json(['data' => $tenant], 201);
    }

    public function show(Tenant $tenant)
    {
        $tenant->load('unit.property', 'user', 'lease', 'payments', 'maintenanceRequests');
        if (!Auth::user()->isAdmin() && $tenant->unit->property->user_id !== Auth::id()) {
            abort(403);
        }
        return response()->json(['data' => $tenant]);
    }

    public function update(Request $request, Tenant $tenant)
    {
        $tenant->load('unit.property');
        if (!Auth::user()->isAdmin() && $tenant->unit->property->user_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'unit_id' => 'sometimes|exists:units,id',
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:tenants,email,'.$tenant->id,
            'phone' => 'nullable|string|max:20',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'id_number' => 'nullable|string|max:50',
            'status' => 'sometimes|in:active,former,prospective',
        ]);

        $tenant->update($data);
        return response()->json(['data' => $tenant]);
    }

    public function destroy(Tenant $tenant)
    {
        $tenant->load('unit.property');
        if (!Auth::user()->isAdmin() && $tenant->unit->property->user_id !== Auth::id()) {
            abort(403);
        }

        if ($tenant->lease()->exists()) {
            return response()->json(['message' => 'Cannot delete tenant with existing leases.'], 422);
        }
        $tenant->delete();
        return response()->json(null, 204);
    }
}
