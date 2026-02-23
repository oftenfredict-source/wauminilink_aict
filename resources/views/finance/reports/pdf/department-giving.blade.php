<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department Giving Report - {{ $startDate->format('M d, Y') }} to {{ $endDate->format('M d, Y') }}</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" />
    <style>
        :root {
            --brand: #940000;
            --brand-light: #fdf0f0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background: #fff;
            font-family: 'Segoe UI', sans-serif;
            line-height: 1.5;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
            font-size: 14px;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 24px;
        }

        .report-header {
            background: var(--brand);
            color: #fff;
            border-radius: 10px;
            padding: 28px 32px;
            margin-bottom: 28px;
            page-break-inside: avoid;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .church-name {
            font-size: 0.82rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            opacity: 0.85;
            margin-bottom: 4px;
        }

        .report-title {
            font-size: 1.9rem;
            font-weight: 800;
            margin: 0 0 6px;
        }

        .report-subtitle {
            font-size: 1rem;
            opacity: 0.9;
        }

        .report-meta-right {
            text-align: right;
            font-size: 0.85rem;
            opacity: 0.8;
        }

        .summary-card {
            border-radius: 10px;
            margin-bottom: 20px;
            page-break-inside: avoid;
            background: #fff;
            border: 2px solid;
        }

        .summary-label {
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 6px;
        }

        .summary-value {
            font-size: 1.55rem;
            font-weight: 800;
            line-height: 1.2;
            margin: 4px 0;
        }

        .small-muted {
            color: #888;
            font-size: 0.82rem;
            margin-top: 4px;
        }

        .card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            margin-bottom: 20px;
            page-break-inside: avoid;
            background: #fff;
            overflow: hidden;
        }

        .card-header {
            background: var(--brand);
            color: #fff;
            font-weight: 700;
            padding: 12px 18px;
            font-size: 0.88rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .card-body {
            padding: 18px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        .table-sm td,
        .table-sm th {
            padding: 9px 12px;
            border: 1px solid #e8e8e8;
            font-size: 0.88rem;
        }

        .table-sm thead th {
            background: var(--brand-light);
            color: #6b0000;
            font-weight: 700;
            border-bottom: 2px solid var(--brand);
            text-align: left;
            font-size: 0.8rem;
            text-transform: uppercase;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .table-sm tfoot th {
            background: var(--brand-light);
            color: #6b0000;
            font-weight: 700;
            border-top: 2px solid var(--brand);
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .table-sm tbody tr:nth-child(even) {
            background: #fafafa;
        }

        .table-light {
            background: var(--brand-light) !important;
            font-weight: 700;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .text-end {
            text-align: right;
        }

        .text-success {
            color: #1a7a45;
        }

        .text-danger {
            color: #c0392b;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .bg-warning {
            background-color: #d97706 !important;
            color: #fff;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .bg-success {
            background-color: #1a7a45 !important;
            color: #fff;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .footer-info {
            margin-top: 36px;
            padding: 16px 20px;
            border-top: 3px solid var(--brand);
            background: var(--brand-light);
            border-radius: 0 0 8px 8px;
            color: #555;
            font-size: 0.82rem;
            text-align: center;
            page-break-inside: avoid;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        @media print {
            @page {
                size: A4;
                margin: 1.2cm 1.5cm;
            }

            body {
                background: white;
            }

            .container {
                max-width: 100%;
                padding: 0;
            }

            .no-print {
                display: none !important;
            }

            .card {
                page-break-inside: avoid;
            }

            .table {
                page-break-inside: auto;
            }

            .table thead {
                display: table-header-group;
            }

            .table tbody {
                display: table-row-group;
            }

            .table tr {
                page-break-inside: avoid;
            }

            .table-sm td,
            .table-sm th {
                border: 1px solid #bbb;
            }

            .report-header {
                background: var(--brand) !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .card-header {
                background: var(--brand) !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .table-sm thead th,
            .table-sm tfoot th {
                background: var(--brand-light) !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .table-light {
                background: var(--brand-light) !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .footer-info {
                background: var(--brand-light) !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="no-print" style="text-align:right;margin-bottom:16px;">
            <button
                style="background:var(--brand);color:#fff;border:none;padding:8px 18px;border-radius:6px;font-size:0.9rem;cursor:pointer;"
                onclick="window.print()">
                <i class="fas fa-print me-1"></i> Print / Save PDF
            </button>
        </div>

        <div class="report-header">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="church-name"><i class="fas fa-church me-1"></i> WauminiLink — Financial Management</div>
                    <h1 class="report-title">Department Giving Report</h1>
                    <div class="report-subtitle">
                        <i class="fas fa-calendar-alt me-1"></i>
                        Period: {{ $startDate->format('M d, Y') }} – {{ $endDate->format('M d, Y') }}
                    </div>
                </div>
                <div class="report-meta-right">
                    <div><strong>Generated</strong></div>
                    <div>{{ now()->format('d M Y') }}</div>
                    <div>{{ now()->format('h:i A') }}</div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card summary-card" style="border-color:var(--brand)">
                    <div class="card-body text-center" style="padding:20px">
                        <div class="summary-label" style="color:var(--brand)">Total Offerings</div>
                        <div class="summary-value" style="color:var(--brand)">TZS
                            {{ number_format($offeringTypes->sum('total_amount'), 0) }}</div>
                        <div class="small-muted">{{ $offeringTypes->sum('transaction_count') }} transactions</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card summary-card border-success">
                    <div class="card-body text-center" style="padding:20px">
                        <div class="summary-label text-success">Total Donations</div>
                        <div class="summary-value text-success">TZS
                            {{ number_format($donationTypes->sum('total_amount'), 0) }}</div>
                        <div class="small-muted">{{ $donationTypes->sum('transaction_count') }} transactions</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card summary-card border-warning">
                    <div class="card-body text-center" style="padding:20px">
                        <div class="summary-label text-warning">Total Pledged</div>
                        <div class="summary-value text-warning">TZS
                            {{ number_format($pledgeTypes->sum('total_pledged'), 0) }}</div>
                        <div class="small-muted">{{ $pledgeTypes->sum('pledge_count') }} pledges</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Combined by Purpose -->
        @if(isset($combinedByPurpose) && !empty($combinedByPurpose))
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-layer-group me-2"></i>Combined Giving by Purpose</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th style="width:25%">Purpose</th>
                                <th class="text-end" style="width:15%">Pledges (Paid)</th>
                                <th class="text-end" style="width:15%">Offerings</th>
                                <th class="text-end" style="width:15%">Donations</th>
                                <th class="text-end" style="width:15%">Combined Total</th>
                                <th class="text-end" style="width:15%">Outstanding</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $grandTotal = 0; @endphp
                            @foreach($combinedByPurpose as $purpose => $data)
                                @php $grandTotal += $data['combined_total']; @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $data['display_name'] }}</strong><br>
                                        <small style="color:#888">{{ $data['pledges']['count'] }} pledges,
                                            {{ $data['offerings']['count'] }} offerings, {{ $data['donations']['count'] }}
                                            donations</small>
                                    </td>
                                    <td class="text-end">
                                        <strong>TZS {{ number_format($data['pledges']['total_paid'], 0) }}</strong><br>
                                        <small style="color:#888">of
                                            {{ number_format($data['pledges']['total_pledged'], 0) }}</small>
                                    </td>
                                    <td class="text-end"><strong>TZS
                                            {{ number_format($data['offerings']['total'], 0) }}</strong></td>
                                    <td class="text-end"><strong>TZS
                                            {{ number_format($data['donations']['total'], 0) }}</strong></td>
                                    <td class="text-end"><strong>TZS {{ number_format($data['combined_total'], 0) }}</strong>
                                    </td>
                                    <td class="text-end">
                                        <span
                                            class="badge {{ $data['pledges']['outstanding'] > 0 ? 'bg-warning' : 'bg-success' }}">
                                            TZS {{ number_format($data['pledges']['outstanding'], 0) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Grand Total</th>
                                <th class="text-end">TZS
                                    {{ number_format(collect($combinedByPurpose)->sum('pledges.total_paid'), 0) }}</th>
                                <th class="text-end">TZS
                                    {{ number_format(collect($combinedByPurpose)->sum('offerings.total'), 0) }}</th>
                                <th class="text-end">TZS
                                    {{ number_format(collect($combinedByPurpose)->sum('donations.total'), 0) }}</th>
                                <th class="text-end">TZS {{ number_format($grandTotal, 0) }}</th>
                                <th class="text-end">TZS
                                    {{ number_format(collect($combinedByPurpose)->sum('pledges.outstanding'), 0) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @endif

        <!-- Offering Types -->
        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-gift me-2"></i>Offering Types Breakdown</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th style="width:30%">Offering Type</th>
                            <th class="text-end" style="width:25%">Total Amount</th>
                            <th class="text-end" style="width:15%">Count</th>
                            <th class="text-end" style="width:15%">Average</th>
                            <th class="text-end" style="width:15%">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalOfferings = $offeringTypes->sum('total_amount'); @endphp
                        @forelse($offeringTypes as $offering)
                            <tr>
                                <td>
                                    @if($offering->offering_type == 'general') General Offering
                                    @elseif(in_array($offering->offering_type, ['special', 'thanksgiving', 'building_fund']))
                                        {{ ucfirst(str_replace('_', ' ', $offering->offering_type)) }}
                                    @else {{ ucfirst($offering->offering_type) }}
                                    @endif
                                </td>
                                <td class="text-end"><strong>TZS {{ number_format($offering->total_amount, 0) }}</strong>
                                </td>
                                <td class="text-end">{{ $offering->transaction_count }}</td>
                                <td class="text-end">TZS
                                    {{ number_format($offering->total_amount / max($offering->transaction_count, 1), 0) }}
                                </td>
                                <td class="text-end">
                                    {{ $totalOfferings > 0 ? number_format(($offering->total_amount / $totalOfferings) * 100, 1) : 0 }}%
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted" style="padding:18px"><i
                                        class="fas fa-inbox me-2"></i>No offering data found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Donation Types -->
        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-heart me-2"></i>Donation Types Breakdown</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th style="width:30%">Donation Type</th>
                            <th class="text-end" style="width:25%">Total Amount</th>
                            <th class="text-end" style="width:15%">Count</th>
                            <th class="text-end" style="width:15%">Average</th>
                            <th class="text-end" style="width:15%">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalDonations = $donationTypes->sum('total_amount'); @endphp
                        @forelse($donationTypes as $donation)
                            <tr>
                                <td>{{ ucfirst($donation->donation_type) }}</td>
                                <td class="text-end"><strong>TZS {{ number_format($donation->total_amount, 0) }}</strong>
                                </td>
                                <td class="text-end">{{ $donation->transaction_count }}</td>
                                <td class="text-end">TZS
                                    {{ number_format($donation->total_amount / max($donation->transaction_count, 1), 0) }}
                                </td>
                                <td class="text-end">
                                    {{ $totalDonations > 0 ? number_format(($donation->total_amount / $totalDonations) * 100, 1) : 0 }}%
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted" style="padding:18px"><i
                                        class="fas fa-inbox me-2"></i>No donation data found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pledge Types -->
        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-handshake me-2"></i>Pledge Types Breakdown</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th style="width:20%">Pledge Type</th>
                            <th class="text-end" style="width:20%">Total Pledged</th>
                            <th class="text-end" style="width:20%">Total Paid</th>
                            <th class="text-end" style="width:20%">Remaining</th>
                            <th class="text-end" style="width:10%">Count</th>
                            <th class="text-end" style="width:10%">Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pledgeTypes as $pledge)
                            @php $completionRate = $pledge->total_pledged > 0 ? ($pledge->total_paid / $pledge->total_pledged) * 100 : 0; @endphp
                            <tr>
                                <td>{{ ucfirst($pledge->pledge_type) }}</td>
                                <td class="text-end"><strong>TZS {{ number_format($pledge->total_pledged, 0) }}</strong>
                                </td>
                                <td class="text-end"><strong>TZS {{ number_format($pledge->total_paid, 0) }}</strong></td>
                                <td class="text-end">TZS
                                    {{ number_format($pledge->total_pledged - $pledge->total_paid, 0) }}</td>
                                <td class="text-end">{{ $pledge->pledge_count }}</td>
                                <td class="text-end">{{ number_format($completionRate, 1) }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted" style="padding:18px"><i
                                        class="fas fa-inbox me-2"></i>No pledge data found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="footer-info">
            <strong>WauminiLink Financial Management System</strong> &mdash; Department Giving Report<br>
            Generated: {{ now()->format('F d, Y \a\t h:i A') }}<br>
            <small style="color:#999;">This is a computer-generated report. No signature required.</small>
        </div>
    </div>
    <script src="{{ asset('assets/js/fontawesome.min.js') }}" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
</body>

</html>