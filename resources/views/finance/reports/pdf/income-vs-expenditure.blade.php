<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income vs Expenditure - {{ $start->format('M Y') }} to {{ $end->format('M Y') }}</title>
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
            color: #1a7a45 !important;
        }

        .text-danger {
            color: #c0392b !important;
        }

        .positive-net {
            color: #1a7a45;
            font-weight: 700;
        }

        .negative-net {
            color: #c0392b;
            font-weight: 700;
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
                    <h1 class="report-title">Income vs Expenditure Report</h1>
                    <div class="report-subtitle">
                        <i class="fas fa-calendar-alt me-1"></i>
                        @if($start->format('Y-m') === $end->format('Y-m') && $start->day === 1 && $end->day === $end->daysInMonth)
                            Period: {{ $start->format('F Y') }}
                        @else
                            Period: {{ $start->format('F d, Y') }} to {{ $end->format('F d, Y') }}
                        @endif
                    </div>
                </div>
                <div class="report-meta-right">
                    <div><strong>Generated</strong></div>
                    <div>{{ now()->format('d M Y') }}</div>
                    <div>{{ now()->format('h:i A') }}</div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card summary-card border-success">
                    <div class="card-body text-center" style="padding:20px">
                        <div class="summary-label text-success">Total Income</div>
                        <div class="summary-value text-success">TZS {{ number_format($totalIncome, 0) }}</div>
                        <div class="small-muted">All revenue sources</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card summary-card border-danger">
                    <div class="card-body text-center" style="padding:20px">
                        <div class="summary-label text-danger">Total Expenses</div>
                        <div class="summary-value text-danger">TZS {{ number_format($totalExpenses, 0) }}</div>
                        <div class="small-muted">All paid expenses</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card summary-card" style="border-color:var(--brand)">
                    <div class="card-body text-center" style="padding:20px">
                        <div class="summary-label" style="color:var(--brand)">Net Position</div>
                        <div class="summary-value {{ $netIncome >= 0 ? 'text-success' : 'text-danger' }}">TZS
                            {{ number_format($netIncome, 0) }}</div>
                        <div class="small-muted">Income minus expenses</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><i class="fas fa-arrow-up me-2"></i>Income Sources</div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th style="width:60%">Source</th>
                                    <th class="text-end" style="width:40%">Amount (TZS)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><i class="fas fa-coins me-2 text-success"></i>Tithes</td>
                                    <td class="text-end"><strong>TZS {{ number_format($tithes, 0) }}</strong></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-gift me-2" style="color:var(--brand)"></i>Offerings</td>
                                    <td class="text-end"><strong>TZS {{ number_format($offerings, 0) }}</strong></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-heart me-2 text-info"></i>Donations</td>
                                    <td class="text-end"><strong>TZS {{ number_format($donations, 0) }}</strong></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-handshake me-2 text-warning"></i>Pledge Payments</td>
                                    <td class="text-end"><strong>TZS {{ number_format($pledgePayments, 0) }}</strong>
                                    </td>
                                </tr>
                                <tr class="table-light">
                                    <td><strong>TOTAL INCOME</strong></td>
                                    <td class="text-end"><strong>TZS {{ number_format($totalIncome, 0) }}</strong></td>
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
                                    <th style="width:50%">Category</th>
                                    <th class="text-end" style="width:30%">Amount (TZS)</th>
                                    <th class="text-end" style="width:20%">Count</th>
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
                                                class="fas fa-inbox me-2"></i>No expenses in selected period</td>
                                    </tr>
                                @endforelse
                                <tr class="table-light">
                                    <td><strong>TOTAL EXPENSES</strong></td>
                                    <td class="text-end"><strong>TZS {{ number_format($totalExpenses, 0) }}</strong>
                                    </td>
                                    <td class="text-end"><strong>{{ $expensesByCategory->sum('count') }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-calendar-alt me-2"></i>Monthly Breakdown</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th style="width:25%">Month</th>
                            <th class="text-end" style="width:25%">Income (TZS)</th>
                            <th class="text-end" style="width:25%">Expenses (TZS)</th>
                            <th class="text-end" style="width:25%">Net (TZS)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($monthlyData as $row)
                            <tr>
                                <td><strong>{{ $row['month'] }}</strong></td>
                                <td class="text-end">TZS {{ number_format($row['income'], 0) }}</td>
                                <td class="text-end">TZS {{ number_format($row['expenses'], 0) }}</td>
                                <td class="text-end"><strong
                                        class="{{ $row['net'] >= 0 ? 'positive-net' : 'negative-net' }}">TZS
                                        {{ number_format($row['net'], 0) }}</strong></td>
                            </tr>
                        @endforeach
                        @if(count($monthlyData) > 0)
                            <tr class="table-light">
                                <td><strong>GRAND TOTAL</strong></td>
                                <td class="text-end"><strong>TZS {{ number_format($totalIncome, 0) }}</strong></td>
                                <td class="text-end"><strong>TZS {{ number_format($totalExpenses, 0) }}</strong></td>
                                <td class="text-end"><strong
                                        class="{{ $netIncome >= 0 ? 'positive-net' : 'negative-net' }}">TZS
                                        {{ number_format($netIncome, 0) }}</strong></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div class="footer-info">
            <strong>WauminiLink Financial Management System</strong> &mdash; Income vs Expenditure Report<br>
            Generated: {{ now()->format('F d, Y \a\t h:i A') }}<br>
            <small style="color:#999;">This is a computer-generated report. No signature required.</small>
        </div>
    </div>
    <script src="{{ asset('assets/js/fontawesome.min.js') }}" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
</body>

</html>