<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Financial Report - {{ $start->format('F Y') }}</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" />
    <style>
        :root {
            --brand: #940000;
            --brand-dark: #6b0000;
            --brand-light: #fdf0f0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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

        /* ── HEADER ── */
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
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            opacity: 0.85;
            margin-bottom: 4px;
        }

        .report-title {
            font-size: 2rem;
            font-weight: 800;
            margin: 0 0 6px 0;
            letter-spacing: -0.5px;
        }

        .report-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            font-weight: 400;
        }

        .report-meta-right {
            text-align: right;
            font-size: 0.85rem;
            opacity: 0.8;
        }

        /* ── SUMMARY CARDS ── */
        .summary-card {
            border-radius: 10px;
            margin-bottom: 20px;
            page-break-inside: avoid;
            background: #fff;
            border: 2px solid;
            overflow: hidden;
        }

        .summary-card .card-body {
            padding: 20px;
            text-align: center;
        }

        .summary-label {
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 6px;
        }

        .summary-value {
            font-size: 1.6rem;
            font-weight: 800;
            line-height: 1.2;
            margin: 4px 0;
        }

        .small-muted {
            color: #888;
            font-size: 0.82rem;
            margin-top: 4px;
        }

        /* ── CARDS ── */
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
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .card-body {
            padding: 18px;
        }

        /* ── TABLES ── */
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
            color: var(--brand-dark);
            font-weight: 700;
            border-bottom: 2px solid var(--brand);
            text-align: left;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .table-sm tbody tr:nth-child(even) {
            background-color: #fafafa;
        }

        .table-light {
            background-color: var(--brand-light) !important;
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

        .text-brand {
            color: var(--brand);
        }

        /* ── NO-PRINT BUTTON ── */
        .no-print-bar {
            text-align: right;
            margin-bottom: 16px;
        }

        /* ── FOOTER ── */
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

        /* ── PRINT ── */
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

            .no-print,
            .no-print-bar {
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

            .footer-info {
                background: var(--brand-light) !important;
                border-top-color: var(--brand) !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- Print Button -->
        <div class="no-print-bar">
            <button class="btn btn-sm"
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
                    <h1 class="report-title">Monthly Financial Report</h1>
                    <div class="report-subtitle">
                        <i class="fas fa-calendar-alt me-1"></i> Period: {{ $start->format('F Y') }}
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
                <div class="card summary-card border-success">
                    <div class="card-body">
                        <div class="summary-label text-success">Total Income</div>
                        <div class="summary-value text-success">TZS {{ number_format($totalIncome, 0) }}</div>
                        <div class="small-muted">All revenue sources</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card summary-card border-danger">
                    <div class="card-body">
                        <div class="summary-label text-danger">Total Expenses</div>
                        <div class="summary-value text-danger">TZS {{ number_format($totalExpenses, 0) }}</div>
                        <div class="small-muted">All paid expenses</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card summary-card" style="border-color: var(--brand);">
                    <div class="card-body">
                        <div class="summary-label text-brand">Net Income</div>
                        <div class="summary-value {{ $netIncome >= 0 ? 'text-success' : 'text-danger' }}">
                            TZS {{ number_format($netIncome, 0) }}
                        </div>
                        <div class="small-muted">Income minus expenses</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Income & Expenses Breakdown -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><i class="fas fa-arrow-up me-2"></i>Income Sources</div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th style="width:55%">Source</th>
                                    <th class="text-end" style="width:30%">Amount (TZS)</th>
                                    <th class="text-end" style="width:15%">Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><i class="fas fa-coins me-2 text-success"></i>Tithes</td>
                                    <td class="text-end"><strong>TZS {{ number_format($totalTithes, 0) }}</strong></td>
                                    <td class="text-end">{{ $tithesCount }}</td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-gift me-2" style="color:var(--brand)"></i>Offerings</td>
                                    <td class="text-end"><strong>TZS {{ number_format($totalOfferings, 0) }}</strong>
                                    </td>
                                    <td class="text-end">{{ $offeringsCount }}</td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-heart me-2 text-info"></i>Donations</td>
                                    <td class="text-end"><strong>TZS {{ number_format($totalDonations, 0) }}</strong>
                                    </td>
                                    <td class="text-end">{{ $donationsCount }}</td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-handshake me-2 text-warning"></i>Pledge Payments</td>
                                    <td class="text-end"><strong>TZS
                                            {{ number_format($totalPledgePayments, 0) }}</strong></td>
                                    <td class="text-end">{{ $pledgePaymentsCount }}</td>
                                </tr>
                                <tr class="table-light">
                                    <td><strong>TOTAL INCOME</strong></td>
                                    <td class="text-end"><strong style="font-size:1.05rem">TZS
                                            {{ number_format($totalIncome, 0) }}</strong></td>
                                    <td class="text-end">
                                        <strong>{{ $tithesCount + $offeringsCount + $donationsCount + $pledgePaymentsCount }}</strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><i class="fas fa-arrow-down me-2"></i>Expenses by Category</div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th style="width:55%">Category</th>
                                    <th class="text-end" style="width:30%">Amount (TZS)</th>
                                    <th class="text-end" style="width:15%">Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($expensesByCategory as $category => $data)
                                    <tr>
                                        <td>{{ ucfirst(str_replace('_', ' ', $category)) }}</td>
                                        <td class="text-end"><strong>TZS {{ number_format($data['total'], 0) }}</strong>
                                        </td>
                                        <td class="text-end">{{ $data['count'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted" style="padding:20px"><i
                                                class="fas fa-inbox me-2"></i>No expenses this month</td>
                                    </tr>
                                @endforelse
                                <tr class="table-light">
                                    <td><strong>TOTAL EXPENSES</strong></td>
                                    <td class="text-end"><strong style="font-size:1.05rem">TZS
                                            {{ number_format($totalExpenses, 0) }}</strong></td>
                                    <td class="text-end"><strong>{{ $expensesCount }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Offerings by Type -->
        @if($offeringsByType->count() > 0)
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-gift me-2"></i>Offerings by Type</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th style="width:40%">Offering Type</th>
                                <th class="text-end" style="width:30%">Amount (TZS)</th>
                                <th class="text-end" style="width:15%">Count</th>
                                <th class="text-end" style="width:15%">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($offeringsByType as $type => $data)
                                <tr>
                                    <td>{{ ucfirst(str_replace('_', ' ', $type)) }}</td>
                                    <td class="text-end"><strong>TZS {{ number_format($data['total'], 0) }}</strong></td>
                                    <td class="text-end">{{ $data['count'] }}</td>
                                    <td class="text-end">
                                        {{ $totalOfferings > 0 ? number_format(($data['total'] / $totalOfferings) * 100, 1) : 0 }}%
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Donations by Type -->
        @if($donationsByType->count() > 0)
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-heart me-2"></i>Donations by Type</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th style="width:40%">Donation Type</th>
                                <th class="text-end" style="width:30%">Amount (TZS)</th>
                                <th class="text-end" style="width:15%">Count</th>
                                <th class="text-end" style="width:15%">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($donationsByType as $type => $data)
                                <tr>
                                    <td>{{ ucfirst(str_replace('_', ' ', $type)) }}</td>
                                    <td class="text-end"><strong>TZS {{ number_format($data['total'], 0) }}</strong></td>
                                    <td class="text-end">{{ $data['count'] }}</td>
                                    <td class="text-end">
                                        {{ $totalDonations > 0 ? number_format(($data['total'] / $totalDonations) * 100, 1) : 0 }}%
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Daily Breakdown -->
        @if(count($dailyData) > 0)
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-calendar-day me-2"></i>Daily Breakdown</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th style="width:30%">Date</th>
                                <th class="text-end" style="width:25%">Income (TZS)</th>
                                <th class="text-end" style="width:25%">Expenses (TZS)</th>
                                <th class="text-end" style="width:20%">Net (TZS)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dailyData as $day)
                                <tr>
                                    <td>{{ $day['date'] }} ({{ $day['day'] }})</td>
                                    <td class="text-end">TZS {{ number_format($day['income'], 0) }}</td>
                                    <td class="text-end">TZS {{ number_format($day['expenses'], 0) }}</td>
                                    <td class="text-end {{ $day['net'] >= 0 ? 'text-success' : 'text-danger' }}"><strong>TZS
                                            {{ number_format($day['net'], 0) }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Top Contributors -->
        @if($topContributors->count() > 0)
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-users me-2"></i>Top Contributors (Top 20)</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th style="width:8%">#</th>
                                <th style="width:20%">Member ID</th>
                                <th style="width:42%">Name</th>
                                <th class="text-end" style="width:30%">Total Giving (TZS)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topContributors as $index => $contributor)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $contributor->member_id }}</td>
                                    <td>{{ $contributor->full_name }}</td>
                                    <td class="text-end"><strong>TZS {{ number_format($contributor->total_giving, 0) }}</strong>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer-info">
            <strong>WauminiLink Financial Management System</strong> &mdash; Monthly Financial Report<br>
            Generated: {{ now()->format('F d, Y \a\t h:i A') }}<br>
            <small style="color:#999;">This is a computer-generated report. No signature required.</small>
        </div>
    </div>

    <script src="{{ asset('assets/js/fontawesome.min.js') }}" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
</body>

</html>