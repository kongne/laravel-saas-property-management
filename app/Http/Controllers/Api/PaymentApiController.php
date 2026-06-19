<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lease;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['lease', 'tenant.user', 'unit.property']);
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

        return response()->json($query->latest()->paginate($request->per_page ?? 15));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'lease_id' => 'required|exists:leases,id',
            'tenant_id' => 'required|exists:tenants,id',
            'unit_id' => 'required|exists:units,id',
            'amount' => 'required|numeric|min:0',
            'late_fee' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'due_date' => 'required|date',
            'paid_date' => 'nullable|date',
            'payment_method' => 'nullable|string|max:50',
            'mobile_money_number' => 'nullable|string|max:30',
            'transaction_reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:pending,paid,partial,overdue,cancelled',
        ]);

        $data['invoice_number'] = 'INV-' . strtoupper(uniqid());
        $lease = Lease::findOrFail($request->lease_id);
        $data['balance'] = ($data['amount'] + ($data['late_fee'] ?? 0)) - ($data['paid_amount'] ?? 0);

        if ($data['balance'] <= 0 && $data['paid_amount'] > 0) {
            $data['status'] = 'paid';
            $data['paid_date'] = $data['paid_date'] ?? now();
        } elseif (($data['paid_amount'] ?? 0) > 0) {
            $data['status'] = 'partial';
        }

        $payment = Payment::create($data);
        return response()->json(['data' => $payment], 201);
    }

    public function show(Payment $payment)
    {
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isLandlord()) {
            $tenant = \App\Models\Tenant::where('user_id', $user->id)->first();
            if (!$tenant || $payment->tenant_id !== $tenant->id) {
                abort(403);
            }
        }
        $payment->load(['lease', 'tenant.user', 'unit.property']);
        return response()->json(['data' => $payment]);
    }

    public function update(Request $request, Payment $payment)
    {
        $payment->load('unit.property');
        if (!Auth::user()->isAdmin() && $payment->unit->property->user_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'amount' => 'sometimes|numeric|min:0',
            'late_fee' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'due_date' => 'sometimes|date',
            'paid_date' => 'nullable|date',
            'payment_method' => 'nullable|string|max:50',
            'mobile_money_number' => 'nullable|string|max:30',
            'transaction_reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'status' => 'sometimes|in:pending,paid,partial,overdue,cancelled',
            'balance' => 'nullable|numeric',
        ]);

        $data['balance'] = ($data['amount'] ?? $payment->amount) + ($data['late_fee'] ?? $payment->late_fee ?? 0) - ($data['paid_amount'] ?? $payment->paid_amount ?? 0);

        if ($data['balance'] <= 0 && ($data['paid_amount'] ?? $payment->paid_amount) > 0) {
            $data['status'] = 'paid';
            $data['paid_date'] = $data['paid_date'] ?? now();
        } elseif (($data['paid_amount'] ?? 0) > 0) {
            $data['status'] = 'partial';
        }

        $payment->update($data);
        return response()->json(['data' => $payment]);
    }

    public function destroy(Payment $payment)
    {
        $payment->load('unit.property');
        if (!Auth::user()->isAdmin() && $payment->unit->property->user_id !== Auth::id()) {
            abort(403);
        }
        $payment->delete();
        return response()->json(null, 204);
    }
}
