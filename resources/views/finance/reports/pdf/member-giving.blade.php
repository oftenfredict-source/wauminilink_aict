<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Giving Report{{ $member ? ' - ' . $member->full_name : '' }} ({{ $startDate->format('M d, Y') }} to
        {{ $endDate->format('M d, Y') }})</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" />
    <style>
        :root {
            --brand: #940000;
            --brand-light: #fdf0f0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: #fff;
            font-family: 'Segoe UI', sans-serif;
            line-height: 1.5;
            color: #1a1a1a;
            font-size: 14px;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 24px;
        }

        /* Header */
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
            margin-bottom: 4px;
        }

        .report-meta-right {
            text-align: right;
            font-size: 0.85rem;
            opacity: 0.8;
            flex-shrink: 0;
        }

        /* Member Info */
        .member-info-card {
            background: var(--brand-light);
            border: 2px solid var(--brand);
            border-radius: 10px;
            padding: 18px 20px;
            margin-bottom: 24px;
            page-break-inside: avoid;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .member-info-title {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--brand);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }

        .member-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
        }

        .member-info-item {
            display: flex;
            gap: 6px;
            align-items: baseline;
            font-size: 0.9rem;
        }

        .member-info-label {
            font-weight: 600;
            color: #555;
        }

        .member-info-value {
            color: #1a1a1a;
        }

        /* Summary Cards */
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
            font-size: 1.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin: 4px 0;
        }

        .small-muted {
            color: #888;
            font-size: 0.82rem;
            margin-top: 4px;
        }

        /* Cards */
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

        /* Tables */
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

        .text-warning {
            color: #d97706;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .bg-success {
            background: #1a7a45 !important;
            color: #fff;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Footer */
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

            .table-sm thead th {
                background: var(--brand-light) !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .table-light {
                background: var(--brand-light) !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .member-info-card {
                background: var(--brand-light) !important;
                border-color: var(--brand) !important;
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

        <!-- Report Header -->
        <div class="report-header">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="church-name"><i class="fas fa-church me-1"></i> WauminiLink — Financial Management</div>
                    <h1 class="report-title">Member Giving Report</h1>
                    @if($member)
                        <div class="report-subtitle"><i class="fas fa-user me-1"></i> {{ $member->full_name }}</div>
                    @endif
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

        <!-- Member Information -->
        @if($member)
            <div class="member-info-card">
                <div class="member-info-title"><i class="fas fa-info-circle me-1"></i>Member Information</div>
                <div class="member-info-grid">
                    <div class="member-info-item"><span class="member-info-label">Full Name:</span><span
                            class="member-info-value">{{ $member->full_name }}</span></div>
                    <div class="member-info-item"><span class="member-info-label">Member ID:</span><span
                            class="member-info-value">{{ $member->member_id }}</span></div>
                    @if($member->phone_number)
                        <div class="member-info-item"><span class="member-info-label">Phone:</span><span
                                class="member-info-value">{{ $member->phone_number }}</span></div>
                    @endif
                    @if($member->email)
                        <div class="member-info-item"><span class="member-info-label">Email:</span><span
                                class="member-info-value">{{ $member->email }}</span></div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Summary Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card summary-card border-success">
                    <div class="card-body text-center" style="padding:18px">
                        <div class="summary-label text-success"><i class="fas fa-coins me-1"></i>Total Tithes</div>
                        <div class="summary-value text-success">TZS {{ number_format($totalTithes, 0) }}</div>
                        <div class="small-muted">{{ $tithes->count() }}
                            {{ $tithes->count() == 1 ? 'transaction' : 'transactions' }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card summary-card" style="border-color:var(--brand)">
                    <div class="card-body text-center" style="padding:18px">
                        <div class="summary-label" style="color:var(--brand)"><i class="fas fa-gift me-1"></i>Total
                            Offerings</div>
                        <div class="summary-value" style="color:var(--brand)">TZS
                            {{ number_format($totalOfferings, 0) }}</div>
                        <div class="small-muted">{{ $offerings->count() }}
                            {{ $offerings->count() == 1 ? 'transaction' : 'transactions' }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card summary-card border-info">
                    <div class="card-body text-center" style="padding:18px">
                        <div class="summary-label text-info"><i class="fas fa-heart me-1"></i>Total Donations</div>
                        <div class="summary-value text-info">TZS {{ number_format($totalDonations, 0) }}</div>
                        <div class="small-muted">{{ $donations->count() }}
                            {{ $donations->count() == 1 ? 'transaction' : 'transactions' }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card summary-card border-warning">
                    <div class="card-body text-center" style="padding:18px">
                        <div class="summary-label text-warning"><i class="fas fa-chart-line me-1"></i>Total Giving</div>
                        <div class="summary-value text-warning">TZS {{ number_format($totalGiving, 0) }}</div>
                        <div class="small-muted">All contributions</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Breakdown -->
        @if(count($monthlyData) > 0)
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-calendar-alt me-2"></i>Monthly Breakdown</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th style="width:20%">Month</th>
                                <th class="text-end" style="width:20%">Tithes (TZS)</th>
                                <th class="text-end" style="width:20%">Offerings (TZS)</th>
                                <th class="text-end" style="width:20%">Donations (TZS)</th>
                                <th class="text-end" style="width:20%">Total (TZS)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthlyData as $month)
                                <tr>
                                    <td><strong>{{ $month['month'] }}</strong></td>
                                    <td class="text-end">TZS {{ number_format($month['tithes'], 0) }}</td>
                                    <td class="text-end">TZS {{ number_format($month['offerings'], 0) }}</td>
                                    <td class="text-end">TZS {{ number_format($month['donations'], 0) }}</td>
                                    <td class="text-end"><strong>TZS {{ number_format($month['total'], 0) }}</strong></td>
                                </tr>
                            @endforeach
                            <tr class="table-light">
                                <td><strong>TOTAL</strong></td>
                                <td class="text-end"><strong>TZS {{ number_format($totalTithes, 0) }}</strong></td>
                                <td class="text-end"><strong>TZS {{ number_format($totalOfferings, 0) }}</strong></td>
                                <td class="text-end"><strong>TZS {{ number_format($totalDonations, 0) }}</strong></td>
                                <td class="text-end"><strong>TZS {{ number_format($totalGiving, 0) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Income Sources & Pledges -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><i class="fas fa-arrow-up me-2"></i>Income Sources</div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th style="width:50%">Source</th>
                                    <th class="text-end" style="width:30%">Amount (TZS)</th>
                                    <th class="text-end" style="width:20%">Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><i class="fas fa-coins me-2 text-success"></i>Tithes</td>
                                    <td class="text-end"><strong>TZS {{ number_format($totalTithes, 0) }}</strong></td>
                                    <td class="text-end">{{ $tithes->count() }}</td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-gift me-2" style="color:var(--brand)"></i>Offerings</td>
                                    <td class="text-end"><strong>TZS {{ number_format($totalOfferings, 0) }}</strong>
                                    </td>
                                    <td class="text-end">{{ $offerings->count() }}</td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-heart me-2 text-info"></i>Donations</td>
                                    <td class="text-end"><strong>TZS {{ number_format($totalDonations, 0) }}</strong>
                                    </td>
                                    <td class="text-end">{{ $donations->count() }}</td>
                                </tr>
                                <tr class="table-light">
                                    <td><strong>TOTAL</strong></td>
                                    <td class="text-end"><strong>TZS {{ number_format($totalGiving, 0) }}</strong></td>
                                    <td class="text-end">
                                        <strong>{{ $tithes->count() + $offerings->count() + $donations->count() }}</strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><i class="fas fa-handshake me-2"></i>Pledges Summary</div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th style="width:50%">Item</th>
                                    <th class="text-end" style="width:30%">Amount (TZS)</th>
                                    <th class="text-end" style="width:20%">Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($pledges->count() > 0)
                                    <tr>
                                        <td><i class="fas fa-file-contract me-2 text-warning"></i>Total Pledged</td>
                                        <td class="text-end"><strong>TZS {{ number_format($totalPledged, 0) }}</strong></td>
                                        <td class="text-end">{{ $pledges->count() }}</td>
                                    </tr>
                                    <tr>
                                        <td><i class="fas fa-check-circle me-2 text-success"></i>Total Paid</td>
                                        <td class="text-end"><strong>TZS {{ number_format($totalPaid, 0) }}</strong></td>
                                        <td class="text-end">{{ $pledges->count() }}</td>
                                    </tr>
                                    <tr>
                                        <td><i
                                                class="fas fa-exclamation-circle me-2 {{ ($totalPledged - $totalPaid) > 0 ? 'text-warning' : 'text-success' }}"></i>Outstanding
                                        </td>
                                        <td class="text-end"><strong
                                                class="{{ ($totalPledged - $totalPaid) > 0 ? 'text-warning' : 'text-success' }}">TZS
                                                {{ number_format($totalPledged - $totalPaid, 0) }}</strong></td>
                                        <td class="text-end">–</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="3" class="text-center text-muted" style="padding:20px"><i
                                                class="fas fa-inbox me-2"></i>No pledges in this period</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tithes Details -->
        @if($tithes->count() > 0)
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-coins me-2"></i>Tithes Details</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th style="width:30%">Date</th>
                                <th class="text-end" style="width:30%">Amount (TZS)</th>
                                <th style="width:25%">Payment Method</th>
                                <th style="width:15%">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tithes as $tithe)
                                <tr>
                                    <td>{{ $tithe->tithe_date->format('M d, Y') }}</td>
                                    <td class="text-end"><strong>TZS {{ number_format($tithe->amount, 0) }}</strong></td>
                                    <td>{{ ucfirst($tithe->payment_method ?? 'N/A') }}</td>
                                    <td><span class="badge bg-success">Approved</span></td>
                                </tr>
                            @endforeach
                            <tr class="table-light">
                                <td><strong>TOTAL</strong></td>
                                <td class="text-end"><strong>TZS {{ number_format($totalTithes, 0) }}</strong></td>
                                <td colspan="2"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Offerings Details -->
        @if($offerings->count() > 0)
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-gift me-2"></i>Offerings Details</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th style="width:25%">Date</th>
                                <th style="width:25%">Type</th>
                                <th class="text-end" style="width:25%">Amount (TZS)</th>
                                <th style="width:25%">Payment Method</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($offerings as $offering)
                                <tr>
                                    <td>{{ $offering->offering_date->format('M d, Y') }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $offering->offering_type)) }}</td>
                                    <td class="text-end"><strong>TZS {{ number_format($offering->amount, 0) }}</strong></td>
                                    <td>{{ ucfirst($offering->payment_method ?? 'N/A') }}</td>
                                </tr>
                            @endforeach
                            <tr class="table-light">
                                <td><strong>TOTAL</strong></td>
                                <td></td>
                                <td class="text-end"><strong>TZS {{ number_format($totalOfferings, 0) }}</strong></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Donations Details -->
        @if($donations->count() > 0)
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-heart me-2"></i>Donations Details</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th style="width:25%">Date</th>
                                <th style="width:25%">Type</th>
                                <th class="text-end" style="width:25%">Amount (TZS)</th>
                                <th style="width:25%">Payment Method</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($donations as $donation)
                                <tr>
                                    <td>{{ $donation->donation_date->format('M d, Y') }}</td>
                                    <td>{{ ucfirst($donation->donation_type) }}</td>
                                    <td class="text-end"><strong>TZS {{ number_format($donation->amount, 0) }}</strong></td>
                                    <td>{{ ucfirst($donation->payment_method ?? 'N/A') }}</td>
                                </tr>
                            @endforeach
                            <tr class="table-light">
                                <td><strong>TOTAL</strong></td>
                                <td></td>
                                <td class="text-end"><strong>TZS {{ number_format($totalDonations, 0) }}</strong></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Pledges Details -->
        @if($pledges->count() > 0)
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-handshake me-2"></i>Pledges Details</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th style="width:20%">Date</th>
                                <th style="width:20%">Type</th>
                                <th class="text-end" style="width:20%">Pledged (TZS)</th>
                                <th class="text-end" style="width:20%">Paid (TZS)</th>
                                <th class="text-end" style="width:20%">Remaining (TZS)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pledges as $pledge)
                                @php $remaining = $pledge->pledge_amount - $pledge->amount_paid; @endphp
                                <tr>
                                    <td>{{ $pledge->pledge_date ? \Carbon\Carbon::parse($pledge->pledge_date)->format('M d, Y') : '–' }}
                                    </td>
                                    <td>{{ ucfirst($pledge->pledge_type) }}</td>
                                    <td class="text-end">TZS {{ number_format($pledge->pledge_amount, 0) }}</td>
                                    <td class="text-end"><strong>TZS {{ number_format($pledge->amount_paid, 0) }}</strong></td>
                                    <td class="text-end {{ $remaining > 0 ? 'text-warning' : 'text-success' }}">
                                        {{ $remaining > 0 ? 'TZS ' . number_format($remaining, 0) : '–' }}</td>
                                </tr>
                            @endforeach
                            <tr class="table-light">
                                <td><strong>TOTAL</strong></td>
                                <td></td>
                                <td class="text-end"><strong>TZS {{ number_format($totalPledged, 0) }}</strong></td>
                                <td class="text-end"><strong>TZS {{ number_format($totalPaid, 0) }}</strong></td>
                                <td class="text-end">
                                    <strong>{{ ($totalPledged - $totalPaid) > 0 ? 'TZS ' . number_format($totalPledged - $totalPaid, 0) : '–' }}</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <div class="footer-info">
            <strong>WauminiLink Financial Management System</strong> &mdash; Member Giving Report<br>
            Generated: {{ now()->format('F d, Y \a\t h:i A') }}<br>
            <small style="color:#999;">This is a computer-generated report. No signature required.</small>
        </div>
    </div>
    <script src="{{ asset('assets/js/fontawesome.min.js') }}" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
</body>

</html>