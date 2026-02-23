<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Performance - {{ $budget->budget_name }} - {{ $start->format('M Y') }} to {{ $end->format('M Y') }}
    </title>
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
            font-size: 1.9rem;
            font-weight: 800;
            margin: 0 0 6px 0;
            letter-spacing: -0.5px;
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

        /* ── SUMMARY CARDS ── */
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
            color: #1a7a45 !important;
        }

        .text-danger {
            color: #c0392b !important;
        }

        .text-warning {
            color: #d97706 !important;
        }

        /* Progress Bar */
        .progress {
            height: 20px;
            background-color: #e9ecef;
            border-radius: 5px;
            overflow: hidden;
            margin-bottom: 10px;
        }

        .progress-bar {
            height: 100%;
            color: #fff;
            text-align: center;
            font-size: 12px;
            line-height: 20px;
            font-weight: bold;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .bg-success {
            background-color: #1a7a45 !important;
        }

        .bg-warning {
            background-color: #d97706 !important;
        }

        .bg-danger {
            background-color: #c0392b !important;
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

            .footer-info {
                background: var(--brand-light) !important;
                border-top-color: var(--brand) !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .progress-bar {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- Report Header -->
        <div class="report-header">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="church-name"><i class="fas fa-church me-1"></i> WauminiLink — Financial Management</div>
                    <h1 class="report-title">Budget Performance Report</h1>
                    <div class="report-subtitle">
                        <i class="fas fa-wallet me-1"></i> Budget: {{ $budget->budget_name }}
                        ({{ $budget->fiscal_year }})
                    </div>
                    <div class="report-subtitle">
                        <i class="fas fa-calendar-alt me-1"></i> Period: {{ $start->format('M d, Y') }} to
                        {{ $end->format('M d, Y') }}
                    </div>
                </div>
                <div class="report-meta-right">
                    <div><strong>Generated</strong></div>
                    <div>{{ now()->format('d M Y') }}</div>
                    <div>{{ now()->format('h:i A') }}</div>
                </div>
            </div>
        </div>

        <!-- Summary Statistics -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card summary-card" style="border-color: var(--brand);">
                    <div class="card-body text-center">
                        <div class="summary-label" style="color: var(--brand);">Total Budget</div>
                        <div class="summary-value" style="color: var(--brand);">TZS
                            {{ number_format($budget->total_budget, 0) }}</div>
                        <div class="small-muted">Approved Allocation</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card summary-card border-success">
                    <div class="card-body text-center">
                        <div class="summary-label text-success">Total Paid</div>
                        <div class="summary-value text-success">TZS {{ number_format($budget->spent_amount, 0) }}</div>
                        <div class="small-muted">Actual Expenditure</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card summary-card border-warning">
                    <div class="card-body text-center">
                        <div class="summary-label text-warning">Pending Commit</div>
                        <div class="summary-value text-warning">TZS
                            {{ number_format($budget->pending_expenses_amount, 0) }}</div>
                        <div class="small-muted">Awaiting Approval/Payment</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div
                    class="card summary-card {{ $budget->remaining_with_pending >= 0 ? 'border-primary' : 'border-danger' }}">
                    <div class="card-body text-center">
                        <div
                            class="summary-label {{ $budget->remaining_with_pending >= 0 ? 'text-primary' : 'text-danger' }}">
                            Available</div>
                        <div
                            class="summary-value {{ $budget->remaining_with_pending >= 0 ? 'text-primary' : 'text-danger' }}">
                            TZS {{ number_format($budget->remaining_with_pending, 0) }}</div>
                        <div class="small-muted">Remaining Balance</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Utilization Progress -->
        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-chart-line me-2"></i>Budget Utilization</div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <div class="h5 mb-0">Commitment Level: {{ $budget->utilization_committed_percentage }}%</div>
                        <small class="text-muted">Percentage of budget committed or spent</small>
                    </div>
                    <div class="col-md-8">
                        <div class="progress" style="height: 30px;">
                            <div class="progress-bar {{ $budget->total_committed > $budget->total_budget ? 'bg-danger' : ($budget->utilization_committed_percentage >= 90 ? 'bg-warning' : 'bg-success') }}"
                                style="width: {{ min($budget->utilization_committed_percentage, 100) }}%">
                                {{ $budget->utilization_committed_percentage }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Breakdown -->
        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-chart-pie me-2"></i>Expenses by Category</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th style="width:35%">Category</th>
                            <th class="text-end" style="width:20%">Total (TZS)</th>
                            <th class="text-end" style="width:15%">Count</th>
                            <th class="text-end" style="width:15%">Average (TZS)</th>
                            <th class="text-end" style="width:15%">% of Budget</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expensesByCategory as $category => $data)
                            <tr>
                                <td><strong>{{ ucfirst($category) }}</strong></td>
                                <td class="text-end"><strong>TZS {{ number_format($data['total'], 0) }}</strong></td>
                                <td class="text-end">{{ $data['count'] }}</td>
                                <td class="text-end text-muted">TZS {{ number_format($data['avg'], 0) }}</td>
                                <td class="text-end">
                                    {{ $budget->total_budget > 0 ? number_format(($data['total'] / $budget->total_budget) * 100, 1) : 0 }}%
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted" style="padding:20px"><i
                                        class="fas fa-inbox me-2"></i>No expense data found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Monthly Performance Table -->
        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-calendar-alt me-2"></i>Monthly Performance Breakdown</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th style="width:25%">Month</th>
                            <th class="text-end" style="width:25%">Paid (TZS)</th>
                            <th class="text-end" style="width:25%">Pending (TZS)</th>
                            <th class="text-end" style="width:25%">Total Committed (TZS)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($monthlyData as $row)
                            <tr>
                                <td><strong>{{ $row['month'] }}</strong></td>
                                <td class="text-end">TZS {{ number_format($row['spent'], 0) }}</td>
                                <td class="text-end text-muted">TZS {{ number_format($row['pending'], 0) }}</td>
                                <td class="text-end"><strong>TZS {{ number_format($row['committed'], 0) }}</strong></td>
                            </tr>
                        @endforeach
                        <tr class="table-light">
                            <td><strong>GRAND TOTAL</strong></td>
                            <td class="text-end"><strong>TZS {{ number_format($budget->spent_amount, 0) }}</strong></td>
                            <td class="text-end"><strong>TZS
                                    {{ number_format($budget->pending_expenses_amount, 0) }}</strong></td>
                            <td class="text-end"><strong style="font-size: 1.1rem;">TZS
                                    {{ number_format($budget->total_committed, 0) }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Expenses -->
        <div class="card">
            <div class="card-header"><i class="fas fa-receipt me-2"></i>Detailed Expense Log (Recent First)</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th style="width:15%">Date</th>
                            <th style="width:25%">Expense Label</th>
                            <th style="width:20%">Category</th>
                            <th class="text-end" style="width:20%">Amount (TZS)</th>
                            <th style="width:20%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses->take(50) as $expense)
                            <tr>
                                <td>{{ $expense->expense_date->format('M d, Y') }}</td>
                                <td>{{ $expense->expense_name }}</td>
                                <td><span class="text-muted small">{{ ucfirst($expense->expense_category) }}</span></td>
                                <td class="text-end"><strong>TZS {{ number_format($expense->amount, 0) }}</strong></td>
                                <td>
                                    @if($expense->status == 'paid')
                                        <span class="text-success fw-bold">Paid</span>
                                    @elseif($expense->status == 'approved')
                                        <span class="text-primary">Approved</span>
                                    @else
                                        <span class="text-warning">Pending</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted" style="padding:20px"><i
                                        class="fas fa-inbox me-2"></i>No expenses recorded</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($expenses->count() > 50)
                    <div class="p-2 text-center text-muted small">
                        Showing top 50 transactions. See system for full history.
                    </div>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="footer-info">
            <strong>WauminiLink Financial Management System</strong> &mdash; Budget Performance Report<br>
            Generated: {{ now()->format('F d, Y \a\t h:i A') }}<br>
            <small style="color:#999;">This is a computer-generated report. No signature required.</small>
        </div>
    </div>

    <script src="{{ asset('assets/js/fontawesome.min.js') }}" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
</body>

</html>