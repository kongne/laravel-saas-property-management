<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTenantRequest;
use App\Models\Tenant;
use App\Models\Unit;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TenantController extends Controller
{
    public function index(Request $request)
    {
        $query = Tenant::with(['user', 'unit.property']);

        if (!Auth::user()->isAdmin()) {
            $query->whereHas('unit.property', function ($q) {
                $q->where('user_id', Auth::id());
            });
        }

        if ($request->search) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $tenants = $query->latest()->paginate(10);
        return view('tenants.index', compact('tenants'));
    }

    public function create()
    {
        $units = Unit::with('property')
            ->where('status', 'available')
            ->whereHas('property', function ($q) {
                if (!Auth::user()->isAdmin()) {
                    $q->where('user_id', Auth::id());
                }
            })
            ->get();

        return view('tenants.create', compact('units'));
    }

    public function store(StoreTenantRequest $request)
    {
        if (!Auth::user()->isAdmin() && Auth::user()->hasReachedLimit('tenants')) {
            return back()->withInput()->with('error', 'You have reached your plan limit for tenants. Please upgrade to add more.');
        }

        $data = $request->validated();
        $tenant = Tenant::create($data);

        $unit = Unit::findOrFail($request->unit_id);
        $unit->update(['status' => 'occupied']);

        ActivityLog::log(Auth::user(), 'tenant_created', "Created tenant: {$tenant->user->name}");

        return redirect()->route('tenants.index')
            ->with('success', 'Tenant assigned successfully.');
    }

    public function show(Tenant $tenant)
    {
        $this->authorizeAccess($tenant);
        $tenant->load(['user', 'unit.property', 'activeLease', 'payments' => function ($q) {
            $q->latest()->limit(10);
        }]);
        return view('tenants.show', compact('tenant'));
    }

    public function edit(Tenant $tenant)
    {
        $this->authorizeAccess($tenant);
        $units = Unit::whereHas('property', function ($q) {
            if (!Auth::user()->isAdmin()) {
                $q->where('user_id', Auth::id());
            }
        })->get();

        return view('tenants.edit', compact('tenant', 'units'));
    }

    public function update(StoreTenantRequest $request, Tenant $tenant)
    {
        $this->authorizeAccess($tenant);
        $oldUnitId = $tenant->unit_id;
        $tenant->update($request->validated());

        if ($oldUnitId !== $request->unit_id) {
            Unit::find($oldUnitId)->update(['status' => 'available']);
            Unit::find($request->unit_id)->update(['status' => 'occupied']);
        }

        ActivityLog::log(Auth::user(), 'tenant_updated', "Updated tenant: {$tenant->user->name}");

        return redirect()->route('tenants.index')
            ->with('success', 'Tenant updated successfully.');
    }

    public function destroy(Tenant $tenant)
    {
        $this->authorizeAccess($tenant);
        $tenant->unit()->update(['status' => 'available']);
        $tenant->delete();

        ActivityLog::log(Auth::user(), 'tenant_deleted', "Deleted tenant: {$tenant->user->name}");

        return redirect()->route('tenants.index')
            ->with('success', 'Tenant removed successfully.');
    }

    private function authorizeAccess(Tenant $tenant): void
    {
        if (!Auth::user()->isAdmin() && $tenant->unit->property->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
