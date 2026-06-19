<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lease;
use App\Models\MaintenanceRequest;
use App\Models\Payment;
use App\Models\Property;
use App\Models\Tenant;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardApiController extends Controller
{
    public function stats()
    {
        $user = Auth::user();

        $monthlyLabels = [];
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $dt = now()->subMonths($i);
            $monthlyLabels[] = $dt->format('M');
            $monthlyData[] = 0;
        }

        if ($user->isAdmin()) {
            $propertyIds = Property::pluck('id');
            $unitIds = Unit::pluck('id');

            $stats = [
                'total_properties' => Property::count(),
                'total_units' => Unit::count(),
                'total_tenants' => Tenant::count(),
                'total_landlords' => User::where('role', 'landlord')->count(),
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
            $stats = $tenant ? [
                'my_unit' => Unit::find($tenant->unit_id),
                'my_lease' => Lease::where('tenant_id', $tenant->id)->where('status', 'active')->first(),
                'pending_payments' => Payment::where('tenant_id', $tenant->id)->where('status', 'pending')->count(),
                'open_maintenance' => MaintenanceRequest::where('tenant_id', $tenant->id)->open()->count(),
            ] : [];
        }

        $paymentsQuery = Payment::query();
        if (isset($unitIds)) {
            $paymentsQuery->whereIn('unit_id', $unitIds);
        } elseif ($user->isTenantUser() && isset($tenant)) {
            $paymentsQuery->where('tenant_id', $tenant->id);
        }

        $paymentStatuses = [
            'paid' => (clone $paymentsQuery)->where('status', 'paid')->count(),
            'pending' => (clone $paymentsQuery)->where('status', 'pending')->count(),
            'overdue' => (clone $paymentsQuery)->overdue()->count(),
            'partial' => (clone $paymentsQuery)->where('status', 'partial')->count(),
        ];

        for ($i = 5; $i >= 0; $i--) {
            $dt = now()->subMonths($i);
            $monthlyData[5 - $i] = (clone $paymentsQuery)
                ->where('status', 'paid')
                ->whereYear('paid_date', $dt->year)
                ->whereMonth('paid_date', $dt->month)
                ->sum('paid_amount');
        }

        $result = array_merge($stats ?? [], [
            'monthly_labels' => $monthlyLabels,
            'monthly_revenue_data' => $monthlyData,
            'payment_statuses' => $paymentStatuses,
        ]);

        return response()->json($result);
    }
}
