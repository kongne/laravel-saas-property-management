<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUnitRequest;
use App\Models\Property;
use App\Models\Unit;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $query = Unit::with('property');

        if (!Auth::user()->isAdmin()) {
            $query->whereHas('property', function ($q) {
                $q->where('user_id', Auth::id());
            });
        }

        if ($request->property_id) {
            $query->where('property_id', $request->property_id);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('unit_number', 'like', "%{$request->search}%")
                  ->orWhere('type', 'like', "%{$request->search}%");
            });
        }

        $units = $query->latest()->paginate(10);
        $properties = Property::where('user_id', Auth::id())->get();

        return view('units.index', compact('units', 'properties'));
    }

    public function create()
    {
        $properties = Property::where('user_id', Auth::id())->where('status', 'active')->get();
        return view('units.create', compact('properties'));
    }

    public function store(StoreUnitRequest $request)
    {
        if (!Auth::user()->isAdmin() && Auth::user()->hasReachedLimit('units')) {
            return back()->withInput()->with('error', 'You have reached your plan limit for units. Please upgrade to add more.');
        }

        $property = Property::findOrFail($request->property_id);
        if (!Auth::user()->isAdmin() && $property->user_id !== Auth::id()) {
            abort(403);
        }

        $unit = Unit::create($request->validated());

        $property->increment('total_units');

        ActivityLog::log(Auth::user(), 'unit_created', "Created unit: {$unit->unit_number}");

        return redirect()->route('units.index')
            ->with('success', 'Unit created successfully.');
    }

    public function show(Unit $unit)
    {
        $this->authorizeAccess($unit);
        $unit->load(['property', 'activeTenant.user', 'activeLease', 'maintenanceRequests' => function ($q) {
            $q->latest();
        }]);
        return view('units.show', compact('unit'));
    }

    public function edit(Unit $unit)
    {
        $this->authorizeAccess($unit);
        $properties = Property::where('user_id', Auth::id())->where('status', 'active')->get();
        return view('units.edit', compact('unit', 'properties'));
    }

    public function update(StoreUnitRequest $request, Unit $unit)
    {
        $this->authorizeAccess($unit);
        $unit->update($request->validated());

        ActivityLog::log(Auth::user(), 'unit_updated', "Updated unit: {$unit->unit_number}");

        return redirect()->route('units.index')
            ->with('success', 'Unit updated successfully.');
    }

    public function destroy(Unit $unit)
    {
        $this->authorizeAccess($unit);

        if ($unit->tenants()->exists()) {
            return back()->with('error', 'Cannot delete unit with active tenants.');
        }

        $unit->property()->decrement('total_units');
        $unit->delete();

        ActivityLog::log(Auth::user(), 'unit_deleted', "Deleted unit: {$unit->unit_number}");

        return redirect()->route('units.index')
            ->with('success', 'Unit deleted successfully.');
    }

    private function authorizeAccess(Unit $unit): void
    {
        if (!Auth::user()->isAdmin() && $unit->property->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
