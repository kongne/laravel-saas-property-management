<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeaseRequest;
use App\Models\Lease;
use App\Models\Tenant;
use App\Models\Unit;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Lease::with(['unit.property', 'tenant.user']);

        if (!Auth::user()->isAdmin()) {
            $query->whereHas('unit.property', function ($q) {
                $q->where('user_id', Auth::id());
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->whereHas('tenant.user', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            });
        }

        $leases = $query->latest()->paginate(10);
        return view('leases.index', compact('leases'));
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

        $tenants = Tenant::with('user', 'unit.property')
            ->whereIn('status', ['pending', 'active'])
            ->when(!Auth::user()->isAdmin(), function ($q) {
                $q->whereHas('unit.property', function ($q2) {
                    $q2->where('user_id', Auth::id());
                });
            })
            ->get();

        return view('leases.create', compact('units', 'tenants'));
    }

    public function store(StoreLeaseRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        $lease = Lease::create($data);

        Unit::find($request->unit_id)->update(['status' => 'occupied']);
        Tenant::find($request->tenant_id)->update(['status' => 'active']);

        ActivityLog::log(Auth::user(), 'lease_created', "Created lease for {$lease->tenant->user->name}");

        return redirect()->route('leases.index')
            ->with('success', 'Lease created successfully.');
    }

    public function show(Lease $lease)
    {
        $this->authorizeAccess($lease);
        $lease->load(['unit.property', 'tenant.user', 'payments' => function ($q) {
            $q->latest();
        }]);
        return view('leases.show', compact('lease'));
    }

    public function edit(Lease $lease)
    {
        $this->authorizeAccess($lease);
        $units = Unit::all();
        $tenants = Tenant::with('user')->get();
        return view('leases.edit', compact('lease', 'units', 'tenants'));
    }

    public function update(StoreLeaseRequest $request, Lease $lease)
    {
        $this->authorizeAccess($lease);
        $lease->update($request->validated());

        ActivityLog::log(Auth::user(), 'lease_updated', "Updated lease #{$lease->id}");

        return redirect()->route('leases.index')
            ->with('success', 'Lease updated successfully.');
    }

    public function destroy(Lease $lease)
    {
        $this->authorizeAccess($lease);

        if ($lease->payments()->exists()) {
            return back()->with('error', 'Cannot delete lease with associated payments.');
        }

        $lease->delete();

        ActivityLog::log(Auth::user(), 'lease_deleted', "Deleted lease #{$lease->id}");

        return redirect()->route('leases.index')
            ->with('success', 'Lease deleted successfully.');
    }

    public function terminate(Lease $lease)
    {
        $this->authorizeAccess($lease);
        $lease->update(['status' => 'terminated']);
        $lease->unit()->update(['status' => 'available']);
        $lease->tenant()->update(['status' => 'past']);

        return back()->with('success', 'Lease terminated successfully.');
    }

    public function renew(Request $request, Lease $lease)
    {
        $this->authorizeAccess($lease);
        $request->validate([
            'end_date' => 'required|date|after:today',
            'rent_amount' => 'required|numeric|min:0',
        ]);

        $lease->update([
            'end_date' => $request->end_date,
            'rent_amount' => $request->rent_amount,
            'status' => 'active',
        ]);

        return back()->with('success', 'Lease renewed successfully.');
    }

    private function authorizeAccess(Lease $lease): void
    {
        if (!Auth::user()->isAdmin() && $lease->unit->property->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
