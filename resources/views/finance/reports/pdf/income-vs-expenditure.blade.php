<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income vs Expenditure Report - {{ $start->format('M Y') }} to {{ $end->format('M Y') }}</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" />
    <style>
        /* Base Styles */
        * {
            box-sizing: border-box;
        }
        
        body { 
            background: #fff; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.5;
            color: #212529;
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Report Header */
        .report-header { 
            border-bottom: 4px solid #0d6efd; 
            margin-bottom: 30px; 
            padding: 25px 30px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 8px;
            page-break-inside: avoid;
        }
        
        .report-title {
            color: #0d6efd;
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }
        
        .report-subtitle {
            color: #6c757d;
            font-size: 1.1rem;
            font-weight: 500;
            margin-top: 5px;
        }
        
        .report-meta {
            color: #6c757d;
            font-size: 0.95rem;
            margin-top: 10px;
        }
        
        /* Summary Cards */
        .summary-card {
            border: 2px solid;
            border-radius: 10px;
            margin-bottom: 20px;
            page-break-inside: avoid;
            background: #fff;
        }
        
        .summary-value {
            font-size: 2rem;
            font-weight: 700;
            margin: 15px 0 10px 0;
            line-height: 1.2;
        }
        
        .summary-label {
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 15px;
        }
        
        .small-muted { 
            color: #6c757d; 
            font-size: 0.85rem; 
            font-weight: 500;
            margin-top: 5px;
        }
        
        /* Cards */
        .card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 20px;
            page-break-inside: avoid;
            background: #fff;
            overflow: hidden;
        }
        
        .card-header {
            background: #0d6efd;
            color: white;
            font-weight: 600;
            padding: 12px 20px;
            font-size: 1rem;
            border-bottom: 2px solid #0056b3;
        }
        
        .card-body {
            padding: 0;
        }
        
        /* Tables */
        .table {
            width: 100%;
            margin-bottom: 0;
            border-collapse: collapse;
        }
        
        .table-sm td, .table-sm th { 
            padding: 10px 15px; 
            border: 1px solid #dee2e6;
            font-size: 0.95rem;
        }
        
        .table-sm thead th {
            background-color: #f8f9fa;
            font-weight: 700;
            color: #495057;
            border-bottom: 3px solid #0d6efd;
            text-align: left;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .table-sm tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .table-sm tbody tr:hover {
            background-color: #e9ecef;
        }
        
        .table-light {
            background-color: #e9ecef !important;
            font-weight: 700;
            font-size: 1rem;
        }
        
        .monthly-table {
            page-break-inside: auto;
        }
        
        .monthly-table thead th {
            background: #495057;
            color: white;
            font-weight: 700;
            padding: 12px 15px;
            border: 1px solid #343a40;
        }
        
        .monthly-table tbody tr {
            page-break-inside: avoid;
        }
        
        .monthly-table tbody td {
            padding: 10px 15px;
            border: 1px solid #dee2e6;
        }
        
        /* Colors */
        .border-success { border-color: #198754 !important; }
        .border-danger { border-color: #dc3545 !important; }
        .border-primary { border-color: #0d6efd !important; }
        .text-success { color: #198754 !important; }
        .text-danger { color: #dc3545 !important; }
        .text-primary { color: #0d6efd !important; }
        .positive-net { color: #198754; font-weight: 700; }
        .negative-net { color: #dc3545; font-weight: 700; }
        
        /* Footer */
        .footer-info {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #dee2e6;
            color: #6c757d;
            font-size: 0.85rem;
            text-align: center;
            page-break-inside: avoid;
        }
        
        /* Two-Page Layout Classes */
        .page-one {
            min-height: calc(100vh - 3cm);
            page-break-after: always;
        }
        
        .page-two {
            min-height: calc(100vh - 3cm);
        }
        
        /* Print Styles */
        @media print {
            @page {
                size: A4;
                margin: 1.5cm;
            }
            
            body { 
                margin: 0;
                padding: 0;
                background: white;
            }
            
            .container {
                max-width: 100%;
                padding: 0;
            }
            
            .no-print { 
                display: none !important; 
            }
            
            .page-break { 
                page-break-after: always; 
            }
            
            .page-break-inside-avoid {
                page-break-inside: avoid;
            }
            
            .report-header {
                page-break-after: avoid;
                margin-bottom: 20px;
            }
            
            /* Page One: Header + Summary Cards + Income/Expenses Breakdown */
            .page-one {
                page-break-after: always;
                page-break-inside: avoid;
            }
            
            /* Page Two: Monthly Breakdown + Footer */
            .page-two {
                page-break-before: always;
            }
            
            .summary-card {
                page-break-inside: avoid;
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
            
            /* Remove shadows and gradients for print */
            .card {
                box-shadow: none;
            }
            
            .summary-card {
                box-shadow: none;
            }
            
            /* Ensure text is black for better printing */
            .text-success, .positive-net {
                color: #000 !important;
            }
            
            .text-danger, .negative-net {
                color: #000 !important;
            }
            
            /* Add borders for better definition */
            .card {
                border: 2px solid #000;
            }
            
            .table-sm td, .table-sm th {
                border: 1px solid #000;
            }
            
            /* Optimize spacing for two-page layout */
            .page-one .row {
                margin-bottom: 15px;
            }
            
            .page-one .card {
                margin-bottom: 15px;
            }
        }
        
        /* Screen-only styles */
        @media screen {
            .summary-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 12px rgba(0,0,0,0.15);
                transition: all 0.3s ease;
            }
        }
    </style>
    <script>
        function triggerPrint() { 
            window.print(); 
        }
        
        // Auto-print on load (optional - comment out if not needed)
        // window.onload = function() { setTimeout(triggerPrint, 500); }
    </script>
</head>
<body>

<div class="container">
    <!-- Report Header -->
    <div class="report-header page-break-inside-avoid">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1 class="report-title">Income vs Expenditure Report</h1>
                <div class="report-subtitle">
                    <i class="fas fa-calendar-alt me-1"></i>
                    @if($start->format('Y-m') === $end->format('Y-m') && $start->day === 1 && $end->day === $end->daysInMonth)
                        Period: {{ $start->format('F Y') }}
                    @else
                        Period: {{ $start->format('F d, Y') }} to {{ $end->format('F d, Y') }}
                    @endif
                </div>
                <div class="report-meta">
                    <i class="fas fa-church me-1"></i>WauminiLink Financial Management System
                </div>
            </div>
            <div class="no-print">
                <button class="btn btn-primary btn-lg" onclick="triggerPrint()" style="padding: 10px 20px; font-size: 1rem;">
                    <i class="fas fa-print me-2"></i>Print Report
                </button>
            </div>
        </div>
    </div>

    <!-- Page One: Summary and Breakdown -->
    <div class="page-one">
        <!-- Summary Cards -->
        <div class="row g-4 mb-4 page-break-inside-avoid">
        <div class="col-md-4">
            <div class="card border-success summary-card">
                <div class="card-body text-center" style="padding: 25px 20px;">
                    <div class="summary-label text-success">Total Income</div>
                    <h2 class="summary-value text-success">TZS {{ number_format($totalIncome, 0) }}</h2>
                    <div class="small-muted">All revenue sources combined</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-danger summary-card">
                <div class="card-body text-center" style="padding: 25px 20px;">
                    <div class="summary-label text-danger">Total Expenses</div>
                    <h2 class="summary-value text-danger">TZS {{ number_format($totalExpenses, 0) }}</h2>
                    <div class="small-muted">All paid expenses</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-primary summary-card">
                <div class="card-body text-center" style="padding: 25px 20px;">
                    <div class="summary-label text-primary">Net Income</div>
                    <h2 class="summary-value {{ $netIncome >= 0 ? 'text-success' : 'text-danger' }}">
                        TZS {{ number_format($netIncome, 0) }}
                    </h2>
                    <div class="small-muted">Income minus expenses</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Income and Expenses Breakdown -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card page-break-inside-avoid">
                <div class="card-header"><strong><i class="fas fa-arrow-up me-2"></i>Income Sources</strong></div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th style="width: 60%;">Source</th>
                                <th class="text-end" style="width: 40%;">Amount (TZS)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><i class="fas fa-coins me-2 text-success"></i>Tithes</td>
                                <td class="text-end"><strong>TZS {{ number_format($tithes, 0) }}</strong></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-gift me-2 text-primary"></i>Offerings</td>
                                <td class="text-end"><strong>TZS {{ number_format($offerings, 0) }}</strong></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-heart me-2 text-info"></i>Donations</td>
                                <td class="text-end"><strong>TZS {{ number_format($donations, 0) }}</strong></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-handshake me-2 text-warning"></i>Pledge Payments</td>
                                <td class="text-end"><strong>TZS {{ number_format($pledgePayments, 0) }}</strong></td>
                            </tr>
                            <tr class="table-light">
                                <td><strong>TOTAL INCOME</strong></td>
                                <td class="text-end"><strong style="font-size: 1.1rem;">TZS {{ number_format($totalIncome, 0) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card page-break-inside-avoid">
                <div class="card-header"><strong><i class="fas fa-arrow-down me-2"></i>Expenses by Category</strong></div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50%;">Category</th>
                                <th class="text-end" style="width: 30%;">Amount (TZS)</th>
                                <th class="text-end" style="width: 20%;">Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expensesByCategory as $category => $data)
                            <tr>
                                <td>{{ ucfirst(str_replace('_', ' ', $category)) }}</td>
                                <td class="text-end"><strong>TZS {{ number_format($data['total'], 0) }}</strong></td>
                                <td class="text-end">{{ $data['count'] }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted" style="padding: 20px;">
                                    <i class="fas fa-inbox me-2"></i>No expenses in selected period
                                </td>
                            </tr>
                            @endforelse
                            <tr class="table-light">
                                <td><strong>TOTAL EXPENSES</strong></td>
                                <td class="text-end"><strong style="font-size: 1.1rem;">TZS {{ number_format($totalExpenses, 0) }}</strong></td>
                                <td class="text-end">
                                    <strong>{{ $expensesByCategory->sum('count') }}</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    </div>
    <!-- End of Page One -->

    <!-- Page Two: Monthly Breakdown -->
    <div class="page-two">
        <!-- Monthly Breakdown -->
        <div class="card mt-4">
            <div class="card-header"><strong><i class="fas fa-calendar-alt me-2"></i>Monthly Breakdown</strong></div>
        <div class="card-body p-0">
            <table class="table table-sm mb-0 monthly-table">
                <thead>
                    <tr>
                        <th style="width: 25%;">Month</th>
                        <th class="text-end" style="width: 25%;">Income (TZS)</th>
                        <th class="text-end" style="width: 25%;">Expenses (TZS)</th>
                        <th class="text-end" style="width: 25%;">Net (TZS)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($monthlyData as $row)
                    <tr>
                        <td><strong>{{ $row['month'] }}</strong></td>
                        <td class="text-end"><strong>TZS {{ number_format($row['income'], 0) }}</strong></td>
                        <td class="text-end"><strong>TZS {{ number_format($row['expenses'], 0) }}</strong></td>
                        <td class="text-end">
                            <strong class="{{ $row['net'] >= 0 ? 'positive-net' : 'negative-net' }}">
                                TZS {{ number_format($row['net'], 0) }}
                            </strong>
                        </td>
                    </tr>
                    @endforeach
                    @if(count($monthlyData) > 0)
                    <tr class="table-light">
                        <td><strong>GRAND TOTAL</strong></td>
                        <td class="text-end"><strong style="font-size: 1.1rem;">TZS {{ number_format($totalIncome, 0) }}</strong></td>
                        <td class="text-end"><strong style="font-size: 1.1rem;">TZS {{ number_format($totalExpenses, 0) }}</strong></td>
                        <td class="text-end">
                            <strong style="font-size: 1.1rem;" class="{{ $netIncome >= 0 ? 'positive-net' : 'negative-net' }}">
                                TZS {{ number_format($netIncome, 0) }}
                            </strong>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        </div>

        <!-- Footer -->
        <div class="footer-info">
        <div style="margin-bottom: 10px;">
            <i class="fas fa-calendar-alt me-1"></i>
            <strong>Generated:</strong> {{ now()->format('F d, Y \a\t h:i A') }}
        </div>
        <div>
            <i class="fas fa-church me-1"></i>
            <strong>WauminiLink</strong> Financial Management System | 
            <i class="fas fa-file-alt me-1"></i>
            Income vs Expenditure Report
        </div>
        <div style="margin-top: 10px; font-size: 0.75rem; color: #adb5bd;">
            This is a computer-generated report. No signature required.
        </div>
        </div>
    </div>
    <!-- End of Page Two -->
</div>

<script src="{{ asset('assets/js/fontawesome.min.js') }}" crossorigin="anonymous"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
</body>
</html>


