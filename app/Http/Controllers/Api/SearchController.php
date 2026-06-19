<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lease;
use App\Models\MaintenanceRequest;
use App\Models\Payment;
use App\Models\Property;
use App\Models\Tenant;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $q = $request->q;
        if (strlen($q) < 2) {
            return response()->json(['results' => []]);
        }

        $user = Auth::user();
        $results = [];

        if ($user->isAdmin() || $user->isLandlord()) {
            $propertyIds = $user->isAdmin()
                ? Property::pluck('id')
                : Property::where('user_id', $user->id)->pluck('id');

            $unitIds = Unit::whereIn('property_id', $propertyIds)->pluck('id');

            $properties = Property::whereIn('id', $propertyIds)
                ->where(function ($query) use ($q) {
                    $query->where('name', 'like', "%{$q}%")
                          ->orWhere('address', 'like', "%{$q}%");
                })->take(5)->get();

            foreach ($properties as $p) {
                $results[] = [
                    'label' => $p->name,
                    'subtext' => $p->address,
                    'url' => route('properties.show', $p),
                    'icon' => 'building',
                ];
            }

            $tenants = Tenant::with('user')->whereIn('unit_id', $unitIds)
                ->whereHas('user', function ($query) use ($q) {
                    $query->where('name', 'like', "%{$q}%")
                          ->orWhere('email', 'like', "%{$q}%");
                })->take(5)->get();

            foreach ($tenants as $t) {
                $results[] = [
                    'label' => $t->user->name,
                    'subtext' => $t->user->email,
                    'url' => route('tenants.show', $t),
                    'icon' => 'people',
                ];
            }

            $leases = Lease::with('tenant.user', 'unit')
                ->whereIn('unit_id', $unitIds)
                ->where(function ($query) use ($q) {
                    $query->whereHas('tenant.user', function ($q2) use ($q) {
                        $q2->where('name', 'like', "%{$q}%");
                    })->orWhere('status', 'like', "%{$q}%");
                })->take(5)->get();

            foreach ($leases as $l) {
                $results[] = [
                    'label' => 'Lease #' . $l->id . ' - ' . ($l->tenant->user->name ?? 'N/A'),
                    'subtext' => ucfirst($l->status) . ' · ' . ($l->unit->unit_number ?? ''),
                    'url' => route('leases.show', $l),
                    'icon' => 'file-text',
                ];
            }

            $payments = Payment::with('tenant.user')
                ->whereIn('unit_id', $unitIds)
                ->where(function ($query) use ($q) {
                    $query->where('invoice_number', 'like', "%{$q}%")
                          ->orWhereHas('tenant.user', function ($q2) use ($q) {
                              $q2->where('name', 'like', "%{$q}%");
                          });
                })->take(5)->get();

            foreach ($payments as $p) {
                $results[] = [
                    'label' => $p->invoice_number,
                    'subtext' => ($p->tenant->user->name ?? 'N/A') . ' · $' . number_format($p->amount, 2),
                    'url' => route('payments.show', $p),
                    'icon' => 'credit-card',
                ];
            }
        }

        if ($user->isTenantUser()) {
            $tenant = Tenant::where('user_id', $user->id)->first();
            if ($tenant) {
                $payments = Payment::where('tenant_id', $tenant->id)
                    ->where('invoice_number', 'like', "%{$q}%")
                    ->take(5)->get();

                foreach ($payments as $p) {
                    $results[] = [
                        'label' => $p->invoice_number,
                        'subtext' => '$' . number_format($p->amount, 2) . ' · ' . ucfirst($p->status),
                        'url' => route('payments.show', $p),
                        'icon' => 'credit-card',
                    ];
                }

                $requests = MaintenanceRequest::where('tenant_id', $tenant->id)
                    ->where('title', 'like', "%{$q}%")
                    ->take(5)->get();

                foreach ($requests as $r) {
                    $results[] = [
                        'label' => $r->title,
                        'subtext' => ucfirst($r->status) . ' · ' . ucfirst($r->priority),
                        'url' => route('maintenance.show', $r),
                        'icon' => 'tools',
                    ];
                }
            }
        }

        return response()->json(['results' => $results]);
    }
}
