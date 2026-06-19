<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceApiController extends Controller
{
    public function index(Request $request)
    {
        $query = MaintenanceRequest::with(['unit.property', 'tenant.user']);
        $user = Auth::user();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->priority) {
            $query->where('priority', $request->priority);
        }

        if (!$user->isAdmin()) {
            if ($user->isLandlord()) {
                $query->whereHas('unit.property', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            } elseif ($user->isTenantUser()) {
                $query->whereHas('tenant', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            }
        }

        return response()->json($query->latest()->paginate($request->per_page ?? 15));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'tenant_id' => 'required|exists:tenants,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'nullable|in:low,medium,high,emergency',
            'category' => 'nullable|string|max:100',
        ]);

        $data['status'] = 'open';
        $data['priority'] = $data['priority'] ?? 'medium';
        $data['user_id'] = Auth::id();
        $data['requested_date'] = now();

        $maintenance = MaintenanceRequest::create($data);
        return response()->json(['data' => $maintenance], 201);
    }

    public function show(MaintenanceRequest $maintenanceRequest)
    {
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isLandlord()) {
            $tenant = \App\Models\Tenant::where('user_id', $user->id)->first();
            if (!$tenant || $maintenanceRequest->tenant_id !== $tenant->id) {
                abort(403);
            }
        } elseif ($user->isLandlord()) {
            $maintenanceRequest->load('unit.property');
            if ($maintenanceRequest->unit->property->user_id !== $user->id) {
                abort(403);
            }
        }
        $maintenanceRequest->load(['unit.property', 'tenant.user']);
        return response()->json(['data' => $maintenanceRequest]);
    }

    public function update(Request $request, MaintenanceRequest $maintenanceRequest)
    {
        $maintenanceRequest->load('unit.property');
        if (!Auth::user()->isAdmin() && $maintenanceRequest->unit->property->user_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'priority' => 'sometimes|in:low,medium,high,emergency',
            'category' => 'nullable|string|max:100',
            'status' => 'sometimes|in:open,in_progress,resolved,closed',
            'assigned_to' => 'nullable|string|max:255',
            'resolution_notes' => 'nullable|string',
            'resolved_at' => 'nullable|date',
        ]);

        $maintenanceRequest->update($data);
        return response()->json(['data' => $maintenanceRequest]);
    }

    public function destroy(MaintenanceRequest $maintenanceRequest)
    {
        $maintenanceRequest->load('unit.property');
        if (!Auth::user()->isAdmin() && $maintenanceRequest->unit->property->user_id !== Auth::id()) {
            abort(403);
        }

        $maintenanceRequest->delete();
        return response()->json(null, 204);
    }
}
