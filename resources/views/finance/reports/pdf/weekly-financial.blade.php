<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Financial Report - {{ $startDate->format('M d') }} to {{ $endDate->format('M d, Y') }}</title>
    <style>
        :root {
            --brand: #940000;
            --brand-light: #fdf0f0;
        }

        @page {
            size: A4;
            margin: 1.2cm;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: #fff;
            font-family: 'Segoe UI', Arial, sans-serif;
            line-height: 1.4;
            color: #222;
            font-size: 11pt;
        }

        .container {
            width: 100%;
        }

        /* Header */
        .report-header {
            background: var(--brand);
            color: #fff;
            padding: 18px 24px;
            margin-bottom: 20px;
            border-radius: 8px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .church-name {
            font-size: 8pt;
            letter-spacing: 2px;
            text-transform: uppercase;
            opacity: 0.85;
            margin-bottom: 3px;
        }

        .report-title {
            font-size: 18pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .report-subtitle {
            font-size: 10pt;
            opacity: 0.9;
            margin-top: 4px;
        }

        .header-right {
            text-align: right;
            font-size: 8.5pt;
            opacity: 0.85;
        }

        /* Summary Grid */
        .summary-grid {
            width: 100%;
            margin-bottom: 18px;
            border-collapse: collapse;
        }

        .summary-box {
            width: 33.33%;
            padding: 10px 12px;
            text-align: center;
            border: 2px solid #eee;
            vertical-align: top;
            background: #fff;
        }

        .summary-box.income {
            border-color: #1a7a45;
        }

        .summary-box.expense {
            border-color: #c0392b;
        }

        .summary-box.net {
            border-color: var(--brand);
        }

        .summary-label {
            font-size: 8pt;
            font-weight: 700;
            text-transform: uppercase;
            color: #777;
            margin-bottom: 4px;
            letter-spacing: 1px;
        }

        .summary-value {
            font-size: 13pt;
            font-weight: 800;
            margin: 0;
        }

        .val-income {
            color: #1a7a45;
        }

        .val-expense {
            color: #c0392b;
        }

        .val-net {
            color: var(--brand);
        }

        .val-pos {
            color: #1a7a45;
        }

        .val-neg {
            color: #c0392b;
        }

        /* Section Headers */
        .section-header {
            background: var(--brand-light);
            border-left: 4px solid var(--brand);
            padding: 5px 12px;
            font-weight: 700;
            font-size: 9.5pt;
            margin-bottom: 8px;
            margin-top: 14px;
            text-transform: uppercase;
            color: #444;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Tables */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .table th {
            background: #fafafa;
            color: #555;
            font-weight: 700;
            border: 1px solid #e0e0e0;
            padding: 6px 10px;
            font-size: 9pt;
            text-align: left;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .table td {
            padding: 6px 10px;
            border-bottom: 1px solid #eee;
            font-size: 10pt;
        }

        .table .row-total {
            background: var(--brand-light);
            font-weight: 700;
            border-top: 2px solid var(--brand);
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .text-end {
            text-align: right !important;
        }

        .text-success {
            color: #1a7a45;
        }

        .text-danger {
            color: #c0392b;
        }

        /* 2-col layout */
        .split-layout {
            width: 100%;
            border-collapse: collapse;
        }

        .split-col {
            width: 48%;
            vertical-align: top;
        }

        .split-spacer {
            width: 4%;
        }

        /* Footer */
        .footer {
            margin-top: 24px;
            padding-top: 12px;
            border-top: 2px solid var(--brand);
            color: #777;
            font-size: 8pt;
            text-align: center;
        }

        /* No-print */
        .no-print {
            text-align: right;
            margin-bottom: 14px;
        }

        @media print {
            @page {
                size: A4;
                margin: 1.2cm;
            }

            .no-print {
                display: none !important;
            }

            .report-header {
                background: var(--brand) !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .table th {
                background: #fafafa !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .table .row-total {
                background: var(--brand-light) !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .section-header {
                background: var(--brand-light) !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .summary-box.income {
                border-color: #1a7a45 !important;
            }

            .summary-box.expense {
                border-color: #c0392b !important;
            }

            .summary-box.net {
                border-color: var(--brand) !important;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="no-print">
            <button
                style="background:var(--brand);color:#fff;border:none;padding:7px 16px;border-radius:5px;font-size:10pt;cursor:pointer;"
                onclick="window.print()">
                &#128438; Print / Save PDF
            </button>
        </div>

        <!-- Header -->
        <div class="report-header">
            <table style="width:100%;border-collapse:collapse;">
                <tr>
                    <td>
                        <div class="church-name">&#9962; WauminiLink &mdash; Financial Management</div>
                        <div class="report-title">Weekly Financial Report</div>
                        <div class="report-subtitle">Period: {{ $startDate->format('M d') }} &mdash;
                            {{ $endDate->format('M d, Y') }}</div>
                    </td>
                    <td class="header-right">
                        <div><strong>Generated</strong></div>
                        <div>{{ now()->format('d M Y') }}</div>
                        <div>{{ now()->format('h:i A') }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Summary -->
        <table class="summary-grid">
            <tr>
                <td class="summary-box income">
                    <div class="summary-label">Total Income</div>
                    <div class="summary-value val-income">TZS {{ number_format($totalIncome, 0) }}</div>
                </td>
                <td class="summary-box expense">
                    <div class="summary-label">Total Expenses</div>
                    <div class="summary-value val-expense">TZS {{ number_format($totalExpenses, 0) }}</div>
                </td>
                <td class="summary-box net">
                    <div class="summary-label">Net Position</div>
                    <div class="summary-value {{ $netIncome >= 0 ? 'val-pos' : 'val-neg' }}">TZS
                        {{ number_format($netIncome, 0) }}</div>
                </td>
            </tr>
        </table>

        <!-- Breakdown Split -->
        <table class="split-layout">
            <tr>
                <td class="split-col">
                    <div class="section-header">Income Breakdown</div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Source</th>
                                <th class="text-end">Amount (TZS)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Tithes ({{ $tithesCount }})</td>
                                <td class="text-end">{{ number_format($totalTithes, 0) }}</td>
                            </tr>
                            <tr>
                                <td>Offerings ({{ $offeringsCount }})</td>
                                <td class="text-end">{{ number_format($totalOfferings, 0) }}</td>
                            </tr>
                            <tr>
                                <td>Donations ({{ $donationsCount }})</td>
                                <td class="text-end">{{ number_format($totalDonations, 0) }}</td>
                            </tr>
                            <tr>
                                <td>Pledges ({{ $pledgePaymentsCount }})</td>
                                <td class="text-end">{{ number_format($totalPledgePayments, 0) }}</td>
                            </tr>
                            <tr class="row-total">
                                <td>TOTAL INCOME</td>
                                <td class="text-end">{{ number_format($totalIncome, 0) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td class="split-spacer"></td>
                <td class="split-col">
                    <div class="section-header">Expenses by Category</div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th class="text-end">Amount (TZS)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expensesByCategory as $category => $data)
                                <tr>
                                    <td>{{ ucfirst(str_replace('_', ' ', $category)) }} ({{ $data['count'] }})</td>
                                    <td class="text-end">{{ number_format($data['total'], 0) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" style="text-align:center;color:#999;padding:16px;">No expenses recorded
                                    </td>
                                </tr>
                            @endforelse
                            <tr class="row-total">
                                <td>TOTAL EXPENSE</td>
                                <td class="text-end">{{ number_format($totalExpenses, 0) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Offerings Detail -->
        @if($offeringsByType->count() > 0)
            <div class="section-header">Offerings Detail</div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Offering Type</th>
                        <th class="text-end">Count</th>
                        <th class="text-end">Amount (TZS)</th>
                        <th class="text-end">Share (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($offeringsByType as $type => $data)
                        <tr>
                            <td>{{ ucfirst(str_replace('_', ' ', $type)) }}</td>
                            <td class="text-end">{{ $data['count'] }}</td>
                            <td class="text-end">{{ number_format($data['total'], 0) }}</td>
                            <td class="text-end">
                                {{ $totalOfferings > 0 ? number_format(($data['total'] / $totalOfferings) * 100, 1) : 0 }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <!-- Daily Activity -->
        <div class="section-header">Daily Activity</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Date / Day</th>
                    <th class="text-end">Income (TZS)</th>
                    <th class="text-end">Expense (TZS)</th>
                    <th class="text-end">Net (TZS)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dailyData as $day)
                    <tr>
                        <td>{{ $day['date'] }} ({{ substr($day['day'], 0, 3) }})</td>
                        <td class="text-end">{{ number_format($day['income'], 0) }}</td>
                        <td class="text-end">{{ number_format($day['expenses'], 0) }}</td>
                        <td class="text-end {{ $day['net'] >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ number_format($day['net'], 0) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Top Contributors -->
        @if($topContributors->count() > 0)
            <div class="section-header">Top Contributions (This Week)</div>
            <table class="table">
                <thead>
                    <tr>
                        <th style="width:8%">#</th>
                        <th style="width:22%">Member ID</th>
                        <th style="width:45%">Name</th>
                        <th class="text-end" style="width:25%">Amount (TZS)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topContributors->take(10) as $index => $contributor)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $contributor->member_id }}</td>
                            <td>{{ $contributor->full_name }}</td>
                            <td class="text-end"><strong>{{ number_format($contributor->total_giving, 0) }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <!-- Footer -->
        <div class="footer">
            <strong>WauminiLink Financial Management System</strong> &mdash; Weekly Financial Report<br>
            Generated: {{ now()->format('F d, Y \a\t h:i A') }} &nbsp;|&nbsp; Confidential Financial Report<br>
            <span style="color:#bbb;">This is a computer-generated report. No signature required.</span>
        </div>
    </div>
</body>

</html>