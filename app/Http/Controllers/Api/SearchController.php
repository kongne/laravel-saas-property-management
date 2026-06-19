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
        $q = trim($request->q);
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
                          ->orWhere('address', 'like', "%{$q}%")
                          ->orWhere('city', 'like', "%{$q}%")
                          ->orWhere('type', 'like', "%{$q}%");
                })->take(5)->get();

            foreach ($properties as $p) {
                $results[] = [
                    'type' => 'property',
                    'label' => $p->name,
                    'subtext' => $p->city . ' · ' . ucfirst($p->type) . ' · ' . ucfirst($p->status),
                    'url' => route('properties.show', $p),
                ];
            }

            $units = Unit::whereIn('property_id', $propertyIds)
                ->where(function ($query) use ($q) {
                    $query->where('unit_number', 'like', "%{$q}%")
                          ->orWhere('type', 'like', "%{$q}%")
                          ->orWhereHas('property', function ($q2) use ($q) {
                              $q2->where('name', 'like', "%{$q}%");
                          });
                })->with('property')->take(5)->get();

            foreach ($units as $u) {
                $results[] = [
                    'type' => 'unit',
                    'label' => $u->unit_number . ' - ' . ($u->property->name ?? ''),
                    'subtext' => ucfirst($u->type) . ' · $' . number_format($u->rent_amount, 0) . '/mo · ' . ucfirst($u->status),
                    'url' => route('units.show', $u),
                ];
            }

            $tenants = Tenant::with('user')->whereIn('unit_id', $unitIds)
                ->whereHas('user', function ($query) use ($q) {
                    $query->where('name', 'like', "%{$q}%")
                          ->orWhere('email', 'like', "%{$q}%")
                          ->orWhere('phone', 'like', "%{$q}%");
                })->take(5)->get();

            foreach ($tenants as $t) {
                $results[] = [
                    'type' => 'tenant',
                    'label' => $t->user->name,
                    'subtext' => $t->user->email . ' · Unit ' . ($t->unit->unit_number ?? 'N/A'),
                    'url' => route('tenants.show', $t),
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
                    'type' => 'lease',
                    'label' => 'Lease #' . $l->id . ' - ' . ($l->tenant->user->name ?? 'N/A'),
                    'subtext' => ucfirst($l->status) . ' · ' . ($l->unit->unit_number ?? ''),
                    'url' => route('leases.show', $l),
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
                    'type' => 'payment',
                    'label' => $p->invoice_number,
                    'subtext' => ($p->tenant->user->name ?? 'N/A') . ' · $' . number_format($p->amount, 2),
                    'url' => route('payments.show', $p),
                ];
            }

            $maintenance = MaintenanceRequest::with('unit.property')
                ->whereIn('unit_id', $unitIds)
                ->where(function ($query) use ($q) {
                    $query->where('title', 'like', "%{$q}%")
                          ->orWhere('description', 'like', "%{$q}%")
                          ->orWhere('status', 'like', "%{$q}%");
                })->take(5)->get();

            foreach ($maintenance as $m) {
                $results[] = [
                    'type' => 'maintenance',
                    'label' => $m->title,
                    'subtext' => ucfirst($m->status) . ' · ' . ucfirst($m->priority) . ' · ' . ($m->unit->unit_number ?? ''),
                    'url' => route('maintenance.show', $m),
                ];
            }
        }

        if ($user->isTenantUser()) {
            $tenant = Tenant::where('user_id', $user->id)->first();
            if ($tenant) {
                $payments = Payment::where('tenant_id', $tenant->id)
                    ->where(function ($query) use ($q) {
                        $query->where('invoice_number', 'like', "%{$q}%")
                              ->orWhere('status', 'like', "%{$q}%");
                    })->take(5)->get();

                foreach ($payments as $p) {
                    $results[] = [
                        'type' => 'payment',
                        'label' => $p->invoice_number,
                        'subtext' => '$' . number_format($p->amount, 2) . ' · ' . ucfirst($p->status),
                        'url' => route('payments.show', $p),
                    ];
                }

                $requests = MaintenanceRequest::where('tenant_id', $tenant->id)
                    ->where(function ($query) use ($q) {
                        $query->where('title', 'like', "%{$q}%")
                              ->orWhere('status', 'like', "%{$q}%");
                    })->take(5)->get();

                foreach ($requests as $r) {
                    $results[] = [
                        'type' => 'maintenance',
                        'label' => $r->title,
                        'subtext' => ucfirst($r->status) . ' · ' . ucfirst($r->priority),
                        'url' => route('maintenance.show', $r),
                    ];
                }
            }
        }

        return response()->json(['results' => $results]);
    }
}
