<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Financial Report - {{ $startDate->format('M d') }} to {{ $endDate->format('M d, Y') }}</title>
    <style>
        /* Modern Minimalist PDF Styles */
        @page {
            size: A4;
            margin: 1cm;
        }

        body {
            background: #fff;
            font-family: 'Helvetica', 'Arial', sans-serif;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
            font-size: 11pt;
        }

        .container {
            width: 100%;
        }

        /* Compact Header */
        .report-header {
            border-bottom: 2px solid #940000;
            margin-bottom: 20px;
            padding-bottom: 10px;
            text-align: center;
        }

        .report-title {
            color: #940000;
            font-weight: bold;
            font-size: 18pt;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .report-subtitle {
            color: #666;
            font-size: 11pt;
            margin-top: 5px;
        }

        /* Summary Grid */
        .summary-grid {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .summary-box {
            width: 33.33%;
            padding: 10px;
            text-align: center;
            border: 1px solid #eee;
        }

        .summary-label {
            font-size: 9pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #777;
            margin-bottom: 5px;
        }

        .summary-value {
            font-size: 14pt;
            font-weight: bold;
            margin: 0;
        }

        .val-income {
            color: #198754;
        }

        .val-expense {
            color: #dc3545;
        }

        .val-net {
            color: #0d6efd;
        }

        /* Section Headers */
        .section-header {
            background-color: #f8f9fa;
            border-left: 4px solid #940000;
            padding: 5px 12px;
            font-weight: bold;
            font-size: 11pt;
            margin-bottom: 10px;
            margin-top: 15px;
            text-transform: uppercase;
            color: #444;
        }

        /* Tables */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .table th,
        .table td {
            padding: 6px 10px;
            border-bottom: 1px solid #eee;
            text-align: left;
            font-size: 10pt;
        }

        .table th {
            background-color: #fafafa;
            color: #555;
            font-weight: bold;
            border-top: 1px solid #eee;
        }

        .table .row-total {
            background-color: #fcfcfc;
            font-weight: bold;
            border-top: 2px solid #eee;
        }

        .text-end {
            text-align: right !important;
        }

        .text-success {
            color: #198754;
        }

        .text-danger {
            color: #dc3545;
        }

        /* Split layout for breakdown */
        .split-layout {
            width: 100%;
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
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            color: #999;
            font-size: 8pt;
            text-align: center;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- Header -->
        <div class="report-header">
            <h1 class="report-title">Weekly Financial Report</h1>
            <div class="report-subtitle">
                Period: {{ $startDate->format('M d') }} — {{ $endDate->format('M d, Y') }}
            </div>
        </div>

        <!-- Summary -->
        <table class="summary-grid">
            <tr>
                <td class="summary-box">
                    <div class="summary-label">Total Income</div>
                    <div class="summary-value val-income">TZS {{ number_format($totalIncome, 0) }}</div>
                </td>
                <td class="summary-box">
                    <div class="summary-label">Total Expenses</div>
                    <div class="summary-value val-expense">TZS {{ number_format($totalExpenses, 0) }}</div>
                </td>
                <td class="summary-box">
                    <div class="summary-label">Net Position</div>
                    <div class="summary-value val-net {{ $netIncome >= 0 ? 'text-success' : 'text-danger' }}">
                        TZS {{ number_format($netIncome, 0) }}
                    </div>
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
                                    <td colspan="2" style="text-align: center; color: #999; padding: 20px;">No expenses
                                        recorded</td>
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

        <!-- Offerings Sub-Breakdown -->
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

        <!-- Daily Summary -->
        <div class="section-header">Daily Activity</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Date / Day</th>
                    <th class="text-end">Income</th>
                    <th class="text-end">Expense</th>
                    <th class="text-end">Net</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dailyData as $day)
                    <tr>
                        <td>{{ $day['date'] }} ({{ substr($day['day'], 0, 3) }})</td>
                        <td class="text-end">{{ number_format($day['income'], 0) }}</td>
                        <td class="text-end">{{ number_format($day['expenses'], 0) }}</td>
                        <td class="text-end {{ $day['net'] >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ number_format($day['net'], 0) }}
                        </td>
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
                        <th style="width: 10%;">#</th>
                        <th style="width: 25%;">Member ID</th>
                        <th style="width: 40%;">Name</th>
                        <th class="text-end" style="width: 25%;">Amount (TZS)</th>
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
            Generated by WauminiLink on {{ now()->format('Y-m-d H:i') }} | Confidential Financial Report
        </div>
    </div>

</body>

</html>