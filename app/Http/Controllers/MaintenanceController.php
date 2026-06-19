<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMaintenanceRequest;
use App\Models\MaintenanceRequest;
use App\Models\Unit;
use App\Notifications\MaintenanceUpdatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $query = MaintenanceRequest::with(['unit.property', 'tenant.user']);
        $user = Auth::user();

        if ($user->isAdmin()) {
            // admin sees all
        } elseif ($user->isLandlord()) {
            $query->whereHas('unit.property', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        } elseif ($user->isTenantUser()) {
            $query->whereHas('tenant', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->priority) {
            $query->where('priority', $request->priority);
        }

        $requests = $query->latest()->paginate(10);
        return view('maintenance.index', compact('requests'));
    }

    public function create()
    {
        $user = Auth::user();
        $units = collect();

        if ($user->isAdmin() || $user->isLandlord()) {
            $units = Unit::with('property')
                ->whereHas('property', function ($q) use ($user) {
                    if (!$user->isAdmin()) {
                        $q->where('user_id', $user->id);
                    }
                })
                ->get();
        } elseif ($user->isTenantUser()) {
            $tenant = \App\Models\Tenant::where('user_id', $user->id)->first();
            if ($tenant && $tenant->unit_id) {
                $units = Unit::with('property')->where('id', $tenant->unit_id)->get();
            }
        }

        return view('maintenance.create', compact('units'));
    }

    public function store(StoreMaintenanceRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();

        if ($user->isTenantUser()) {
            $tenant = \App\Models\Tenant::where('user_id', $user->id)->first();
            if ($tenant) {
                $data['tenant_id'] = $tenant->id;
                $data['unit_id'] = $tenant->unit_id;
            }
        }

        $data['user_id'] = $user->id;
        $data['requested_date'] = $data['requested_date'] ?? now();

        $request = MaintenanceRequest::create($data);

        $request->load('unit.property.user', 'tenant.user');
        if ($request->unit?->property?->user) {
            $request->unit->property->user->notify(new MaintenanceUpdatedNotification($request, 'created'));
        }

        return redirect()->route('maintenance.index')
            ->with('success', 'Maintenance request created successfully.');
    }

    public function show(MaintenanceRequest $maintenanceRequest)
    {
        $user = Auth::user();
        if ($user->isTenantUser()) {
            $tenant = \App\Models\Tenant::where('user_id', $user->id)->first();
            if (!$tenant || $maintenanceRequest->tenant_id !== $tenant->id) {
                abort(403);
            }
        } else {
            $this->authorizeAccess($maintenanceRequest);
        }
        $maintenanceRequest->load(['unit.property', 'tenant.user']);
        return view('maintenance.show', compact('maintenanceRequest'));
    }

    public function edit(MaintenanceRequest $maintenanceRequest)
    {
        $this->authorizeAccess($maintenanceRequest);
        $units = Unit::all();
        return view('maintenance.edit', compact('maintenanceRequest', 'units'));
    }

    public function update(StoreMaintenanceRequest $request, MaintenanceRequest $maintenanceRequest)
    {
        $this->authorizeAccess($maintenanceRequest);
        $maintenanceRequest->update($request->validated());

        return redirect()->route('maintenance.index')
            ->with('success', 'Maintenance request updated successfully.');
    }

    public function destroy(MaintenanceRequest $maintenanceRequest)
    {
        $this->authorizeAccess($maintenanceRequest);
        $maintenanceRequest->delete();

        return redirect()->route('maintenance.index')
            ->with('success', 'Maintenance request deleted successfully.');
    }

    public function resolve(Request $request, MaintenanceRequest $maintenanceRequest)
    {
        $this->authorizeAccess($maintenanceRequest);
        $request->validate([
            'resolution_notes' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
        ]);

        $maintenanceRequest->resolve($request->resolution_notes, $request->cost);

        $maintenanceRequest->load('tenant.user');
        if ($maintenanceRequest->tenant?->user) {
            $maintenanceRequest->tenant->user->notify(new MaintenanceUpdatedNotification($maintenanceRequest, 'resolved'));
        }

        return back()->with('success', 'Maintenance request resolved.');
    }

    public function assign(Request $request, MaintenanceRequest $maintenanceRequest)
    {
        $this->authorizeAccess($maintenanceRequest);
        $request->validate([
            'assigned_to' => 'required|string|max:255',
        ]);

        $maintenanceRequest->update([
            'assigned_to' => $request->assigned_to,
            'status' => 'in_progress',
        ]);

        $maintenanceRequest->load('tenant.user');
        if ($maintenanceRequest->tenant?->user) {
            $maintenanceRequest->tenant->user->notify(new MaintenanceUpdatedNotification($maintenanceRequest, 'assigned'));
        }

        return back()->with('success', 'Maintenance assigned successfully.');
    }

    private function authorizeAccess(MaintenanceRequest $maintenanceRequest): void
    {
        if (!Auth::user()->isAdmin() && $maintenanceRequest->unit->property->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
