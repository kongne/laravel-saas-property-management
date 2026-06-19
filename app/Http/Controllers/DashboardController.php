<?php

namespace App\Http\Controllers;

use App\Models\Lease;
use App\Models\MaintenanceRequest;
use App\Models\Payment;
use App\Models\Property;
use App\Models\Tenant;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $stats = [];

        if ($user->isAdmin()) {
            $stats = [
                'total_properties' => Property::count(),
                'total_units' => Unit::count(),
                'total_tenants' => Tenant::count(),
                'total_landlords' => \App\Models\User::where('role', 'landlord')->count(),
                'active_leases' => Lease::where('status', 'active')->count(),
                'pending_payments' => Payment::where('status', 'pending')->count(),
                'overdue_payments' => Payment::overdue()->count(),
                'open_maintenance' => MaintenanceRequest::open()->count(),
                'monthly_revenue' => Payment::where('status', 'paid')
                    ->whereMonth('paid_date', now()->month)
                    ->sum('paid_amount'),
            ];
        } elseif ($user->isLandlord()) {
            $propertyIds = Property::where('user_id', $user->id)->pluck('id');
            $unitIds = Unit::whereIn('property_id', $propertyIds)->pluck('id');

            $stats = [
                'total_properties' => Property::where('user_id', $user->id)->count(),
                'total_units' => Unit::whereIn('property_id', $propertyIds)->count(),
                'occupied_units' => Unit::whereIn('property_id', $propertyIds)->where('status', 'occupied')->count(),
                'available_units' => Unit::whereIn('property_id', $propertyIds)->where('status', 'available')->count(),
                'total_tenants' => Tenant::whereIn('unit_id', $unitIds)->count(),
                'active_leases' => Lease::whereIn('unit_id', $unitIds)->where('status', 'active')->count(),
                'pending_payments' => Payment::whereIn('unit_id', $unitIds)->where('status', 'pending')->count(),
                'overdue_payments' => Payment::whereIn('unit_id', $unitIds)->overdue()->count(),
                'open_maintenance' => MaintenanceRequest::whereIn('unit_id', $unitIds)->open()->count(),
                'monthly_revenue' => Payment::whereIn('unit_id', $unitIds)
                    ->where('status', 'paid')
                    ->whereMonth('paid_date', now()->month)
                    ->sum('paid_amount'),
            ];
        } elseif ($user->isTenantUser()) {
            $tenant = Tenant::where('user_id', $user->id)->first();
            if ($tenant) {
                $stats = [
                    'my_unit' => Unit::find($tenant->unit_id),
                    'my_lease' => Lease::where('tenant_id', $tenant->id)->where('status', 'active')->first(),
                    'my_payments' => Payment::where('tenant_id', $tenant->id)->latest()->take(5)->get(),
                    'pending_payments' => Payment::where('tenant_id', $tenant->id)->where('status', 'pending')->count(),
                    'my_maintenance' => MaintenanceRequest::where('tenant_id', $tenant->id)->open()->count(),
                ];
            }
        }

        return view('dashboard.index', compact('stats', 'user'));
    }
}
