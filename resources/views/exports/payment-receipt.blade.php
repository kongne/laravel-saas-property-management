<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #1e293b; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #4f46e5; padding-bottom: 20px; }
        .header h1 { font-size: 24px; color: #4f46e5; margin: 0 0 5px; }
        .header p { margin: 0; color: #64748b; font-size: 11px; }
        .invoice-info { margin-bottom: 30px; }
        .invoice-info table { width: 100%; }
        .invoice-info td { padding: 3px 0; }
        .label { color: #64748b; font-size: 10px; text-transform: uppercase; }
        .value { font-weight: bold; }
        .details { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .details th { background: #4f46e5; color: white; padding: 8px 12px; text-align: left; font-size: 11px; text-transform: uppercase; }
        .details td { padding: 8px 12px; border-bottom: 1px solid #e2e8f0; }
        .total { text-align: right; font-size: 16px; font-weight: bold; color: #4f46e5; margin-top: 20px; }
        .footer { text-align: center; color: #94a3b8; font-size: 10px; margin-top: 40px; border-top: 1px solid #e2e8f0; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name') }}</h1>
        <p>Payment Receipt</p>
    </div>

    <div class="invoice-info">
        <table>
            <tr>
                <td><span class="label">Invoice #</span><br><span class="value">{{ $payment->invoice_number }}</span></td>
                <td><span class="label">Date</span><br><span class="value">{{ $payment->paid_date?->format('M d, Y') ?? $payment->due_date->format('M d, Y') }}</span></td>
                <td><span class="label">Status</span><br><span class="value">{{ ucfirst($payment->status) }}</span></td>
            </tr>
        </table>
    </div>

    <table class="details">
        <thead>
            <tr>
                <th>Description</th>
                <th>Tenant</th>
                <th>Unit</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Rent Payment - {{ $payment->lease?->start_date?->format('M Y') ?? 'N/A' }}</td>
                <td>{{ $payment->tenant->user->name ?? 'N/A' }}</td>
                <td>{{ $payment->unit->unit_number ?? 'N/A' }} ({{ $payment->unit->property->name ?? 'N/A' }})</td>
                <td>${{ number_format($payment->amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="total">
        Total Paid: ${{ number_format($payment->paid_amount ?? $payment->amount, 2) }}
    </div>

    <div class="footer">
        <p>{{ config('app.name') }} &mdash; Property Management Solution</p>
        <p>Thank you for your payment.</p>
    </div>
</body>
</html>
