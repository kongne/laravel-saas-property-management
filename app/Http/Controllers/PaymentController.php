<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Models\Lease;
use App\Models\Payment;
use App\Models\ActivityLog;
use App\Notifications\PaymentReceiptNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
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

        if ($request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->date_from) {
            $query->where('due_date', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->where('due_date', '<=', $request->date_to);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('invoice_number', 'like', "%{$request->search}%")
                  ->orWhereHas('tenant.user', function ($q2) use ($request) {
                      $q2->where('name', 'like', "%{$request->search}%");
                  });
            });
        }

        $perPage = $request->per_page ?? 10;
        $payments = $query->latest()->paginate($perPage);
        $totalDue = (clone $query)->whereIn('status', ['pending', 'overdue'])->sum('amount');
        $totalCollected = (clone $query)->where('status', 'paid')->sum('paid_amount');

        return view('payments.index', compact('payments', 'totalDue', 'totalCollected'));
    }

    public function create()
    {
        $leases = Lease::with(['unit.property', 'tenant.user'])
            ->where('status', 'active')
            ->whereHas('unit.property', function ($q) {
                if (!Auth::user()->isAdmin()) {
                    $q->where('user_id', Auth::id());
                }
            })
            ->get();

        return view('payments.create', compact('leases'));
    }

    public function store(StorePaymentRequest $request)
    {
        $data = $request->validated();
        $data['invoice_number'] = 'INV-' . strtoupper(uniqid());

        $lease = Lease::findOrFail($request->lease_id);
        $data['balance'] = ($data['amount'] + ($data['late_fee'] ?? 0)) - ($data['paid_amount'] ?? 0);

        if ($data['balance'] <= 0 && ($data['paid_amount'] ?? 0) > 0) {
            $data['status'] = 'paid';
            $data['paid_date'] = $data['paid_date'] ?? now();
        } elseif (($data['paid_amount'] ?? 0) > 0) {
            $data['status'] = 'partial';
        }

        $payment = Payment::create($data);

        ActivityLog::log(Auth::user(), 'payment_created', "Created payment INV-{$payment->invoice_number}");

        return redirect()->route('payments.index')
            ->with('success', 'Payment recorded successfully.');
    }

    public function show(Payment $payment)
    {
        $user = Auth::user();
        if ($user->isTenantUser()) {
            $tenant = \App\Models\Tenant::where('user_id', $user->id)->first();
            if (!$tenant || $payment->tenant_id !== $tenant->id) {
                abort(403);
            }
        } else {
            $this->authorizeAccess($payment);
        }
        $payment->load(['lease', 'tenant.user', 'unit.property']);
        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $this->authorizeAccess($payment);
        $leases = Lease::with(['unit.property', 'tenant.user'])->get();
        return view('payments.edit', compact('payment', 'leases'));
    }

    public function update(StorePaymentRequest $request, Payment $payment)
    {
        $this->authorizeAccess($payment);
        $data = $request->validated();
        $data['balance'] = ($data['amount'] + ($data['late_fee'] ?? 0)) - ($data['paid_amount'] ?? 0);

        if ($data['balance'] <= 0 && ($data['paid_amount'] ?? 0) > 0) {
            $data['status'] = 'paid';
            $data['paid_date'] = $data['paid_date'] ?? now();
        } elseif (($data['paid_amount'] ?? 0) > 0) {
            $data['status'] = 'partial';
        }

        $payment->update($data);

        ActivityLog::log(Auth::user(), 'payment_updated', "Updated payment INV-{$payment->invoice_number}");

        return redirect()->route('payments.index')
            ->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        $this->authorizeAccess($payment);
        $payment->delete();

        ActivityLog::log(Auth::user(), 'payment_deleted', "Deleted payment INV-{$payment->invoice_number}");

        return redirect()->route('payments.index')
            ->with('success', 'Payment deleted successfully.');
    }

    public function markAsPaid(Request $request, Payment $payment)
    {
        $this->authorizeAccess($payment);
        $request->validate([
            'paid_amount' => 'required|numeric|min:0',
            'payment_method' => 'nullable|string|in:cash,check,bank_transfer,credit_card,mobile_money,orange_money,mtn_money,other',
            'mobile_money_number' => 'nullable|string|max:30|required_if:payment_method,orange_money|required_if:payment_method,mtn_money',
            'transaction_reference' => 'nullable|string',
        ]);

        $payment->markAsPaid(
            $request->paid_amount,
            $request->payment_method,
            $request->transaction_reference
        );

        $payment->update(['mobile_money_number' => $request->mobile_money_number]);

        $payment->load('tenant.user');
        if ($payment->tenant?->user) {
            $payment->tenant->user->notify(new PaymentReceiptNotification($payment));
        }

        return back()->with('success', 'Payment marked as paid.');
    }

    public function receipt(Payment $payment)
    {
        $this->authorizeAccess($payment);
        $payment->load(['lease', 'tenant.user', 'unit.property']);
        return view('payments.receipt', compact('payment'));
    }

    private function authorizeAccess(Payment $payment): void
    {
        if (!Auth::user()->isAdmin() && $payment->unit->property->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
