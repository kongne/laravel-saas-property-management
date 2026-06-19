<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaseApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Lease::with(['unit.property', 'tenant.user']);
        $user = Auth::user();

        if ($request->status) {
            $query->where('status', $request->status);
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
            'tenant_id' => 'required|exists:tenants,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'rent_amount' => 'required|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
            'payment_due_day' => 'required|integer|between:1,31',
            'status' => 'nullable|in:active,pending,expired,terminated',
            'terms' => 'nullable|string',
        ]);

        $unit = \App\Models\Unit::with('property')->findOrFail($request->unit_id);
        if (!Auth::user()->isAdmin() && $unit->property->user_id !== Auth::id()) {
            abort(403);
        }

        $data['user_id'] = Auth::id();
        $lease = Lease::create($data);
        return response()->json(['data' => $lease], 201);
    }

    public function show(Lease $lease)
    {
        $lease->load(['unit.property', 'tenant.user', 'payments', 'maintenanceRequests']);
        if (!Auth::user()->isAdmin() && $lease->unit->property->user_id !== Auth::id()) {
            abort(403);
        }
        return response()->json(['data' => $lease]);
    }

    public function update(Request $request, Lease $lease)
    {
        $lease->load('unit.property');
        if (!Auth::user()->isAdmin() && $lease->unit->property->user_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'unit_id' => 'sometimes|exists:units,id',
            'tenant_id' => 'sometimes|exists:tenants,id',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after:start_date',
            'rent_amount' => 'sometimes|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
            'payment_due_day' => 'sometimes|integer|between:1,31',
            'status' => 'sometimes|in:active,pending,expired,terminated',
            'terms' => 'nullable|string',
        ]);

        $lease->update($data);
        return response()->json(['data' => $lease]);
    }

    public function destroy(Lease $lease)
    {
        $lease->load('unit.property');
        if (!Auth::user()->isAdmin() && $lease->unit->property->user_id !== Auth::id()) {
            abort(403);
        }

        if ($lease->payments()->exists()) {
            return response()->json(['message' => 'Cannot delete lease with existing payments.'], 422);
        }
        $lease->delete();
        return response()->json(null, 204);
    }
}
