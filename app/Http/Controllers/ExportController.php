<?php

namespace App\Http\Controllers;

use App\Models\Lease;
use App\Models\MaintenanceRequest;
use App\Models\Payment;
use App\Models\Property;
use App\Models\Tenant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
    public function payments(Request $request)
    {
        $query = Payment::with(['tenant.user', 'unit.property', 'lease']);
        $user = Auth::user();

        if ($user->isLandlord()) {
            $query->whereHas('unit.property', fn($q) => $q->where('user_id', $user->id));
        } elseif ($user->isTenantUser()) {
            $query->whereHas('tenant', fn($q) => $q->where('user_id', $user->id));
        }

        if ($request->status) $query->where('status', $request->status);
        if ($request->payment_method) $query->where('payment_method', $request->payment_method);
        if ($request->date_from) $query->where('due_date', '>=', $request->date_from);
        if ($request->date_to) $query->where('due_date', '<=', $request->date_to);
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('invoice_number', 'like', "%{$request->search}%")
                  ->orWhereHas('tenant.user', fn($q2) => $q2->where('name', 'like', "%{$request->search}%"));
            });
        }

        $payments = $query->latest()->get();
        $filename = 'payments-' . now()->format('Y-m-d-His') . '.csv';

        $handle = fopen('php://temp', 'w+');
        fputs($handle, "\xEF\xBB\xBF");
        fputcsv($handle, ['Invoice', 'Tenant', 'Unit', 'Amount', 'Paid', 'Status', 'Due Date', 'Method', 'Date Paid']);

        foreach ($payments as $p) {
            fputcsv($handle, [
                $p->invoice_number,
                $p->tenant->user->name ?? 'N/A',
                $p->unit->unit_number ?? 'N/A',
                number_format($p->amount, 2),
                number_format($p->paid_amount ?? 0, 2),
                $p->status,
                $p->due_date->format('Y-m-d'),
                $p->payment_method,
                $p->paid_date?->format('Y-m-d'),
            ]);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function leases(Request $request)
    {
        $query = Lease::with(['tenant.user', 'unit.property']);

        if (!Auth::user()->isAdmin()) {
            $query->whereHas('unit.property', fn($q) => $q->where('user_id', Auth::id()));
        }

        $leases = $query->latest()->get();
        $filename = 'leases-' . now()->format('Y-m-d-His') . '.csv';

        $handle = fopen('php://temp', 'w+');
        fputs($handle, "\xEF\xBB\xBF");
        fputcsv($handle, ['Tenant', 'Unit', 'Property', 'Start', 'End', 'Rent', 'Status']);

        foreach ($leases as $l) {
            fputcsv($handle, [
                $l->tenant->user->name ?? 'N/A',
                $l->unit->unit_number ?? 'N/A',
                $l->unit->property->name ?? 'N/A',
                $l->start_date->format('Y-m-d'),
                $l->end_date->format('Y-m-d'),
                number_format($l->rent_amount ?? 0, 2),
                $l->status,
            ]);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function properties(Request $request)
    {
        $query = Property::withCount('units');
        if (!Auth::user()->isAdmin()) {
            $query->where('user_id', Auth::id());
        }
        $properties = $query->latest()->get();
        $filename = 'properties-' . now()->format('Y-m-d-His') . '.csv';

        $handle = fopen('php://temp', 'w+');
        fputs($handle, "\xEF\xBB\xBF");
        fputcsv($handle, ['Name', 'Type', 'City', 'District', 'Address', 'Units', 'Status', 'Featured', 'Created']);

        foreach ($properties as $p) {
            fputcsv($handle, [
                $p->name,
                $p->type,
                $p->city,
                $p->district ?? '',
                $p->address,
                $p->units_count ?? 0,
                $p->status,
                $p->featured ? 'Yes' : 'No',
                $p->created_at->format('Y-m-d'),
            ]);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function maintenance(Request $request)
    {
        $query = MaintenanceRequest::with(['unit.property', 'tenant.user']);
        $user = Auth::user();
        if ($user->isLandlord()) {
            $query->whereHas('unit.property', fn($q) => $q->where('user_id', $user->id));
        } elseif ($user->isTenantUser()) {
            $query->whereHas('tenant', fn($q) => $q->where('user_id', $user->id));
        }
        $items = $query->latest()->get();
        $filename = 'maintenance-' . now()->format('Y-m-d-His') . '.csv';

        $handle = fopen('php://temp', 'w+');
        fputs($handle, "\xEF\xBB\xBF");
        fputcsv($handle, ['Title', 'Property', 'Unit', 'Tenant', 'Priority', 'Status', 'Category', 'Created']);

        foreach ($items as $m) {
            fputcsv($handle, [
                $m->title,
                $m->unit->property->name ?? 'N/A',
                $m->unit->unit_number ?? 'N/A',
                $m->tenant->user->name ?? 'N/A',
                $m->priority,
                $m->status,
                $m->category ?? '',
                $m->created_at->format('Y-m-d'),
            ]);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function paymentPdf(Payment $payment)
    {
        $payment->load(['tenant.user', 'unit.property', 'lease']);
        $this->authorizePaymentAccess($payment);

        $pdf = Pdf::loadView('exports.payment-receipt', compact('payment'));

        return $pdf->download("receipt-{$payment->invoice_number}.pdf");
    }

    private function authorizePaymentAccess(Payment $payment): void
    {
        $user = Auth::user();
        if ($user->isAdmin()) return;
        if ($user->isLandlord()) {
            $propertyUserId = $payment->unit?->property?->user_id;
            if ($propertyUserId && $propertyUserId === $user->id) return;
        }
        if ($user->isTenantUser()) {
            if ($payment->tenant?->user_id === $user->id) return;
        }
        abort(403);
    }

    public function tenants(Request $request)
    {
        $query = Tenant::with(['user', 'unit.property']);

        if (!Auth::user()->isAdmin()) {
            $query->whereHas('unit.property', fn($q) => $q->where('user_id', Auth::id()));
        }

        $tenants = $query->latest()->get();
        $filename = 'tenants-' . now()->format('Y-m-d-His') . '.csv';

        $handle = fopen('php://temp', 'w+');
        fputs($handle, "\xEF\xBB\xBF");
        fputcsv($handle, ['Name', 'Email', 'Phone', 'Unit', 'Property', 'Status', 'Move In']);

        foreach ($tenants as $t) {
            fputcsv($handle, [
                $t->user->name ?? 'N/A',
                $t->user->email ?? 'N/A',
                $t->phone ?? 'N/A',
                $t->unit->unit_number ?? 'N/A',
                $t->unit->property->name ?? 'N/A',
                $t->status,
                $t->move_in_date?->format('Y-m-d'),
            ]);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
