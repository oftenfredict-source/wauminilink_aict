<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giving Receipt - {{ $member->full_name }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background: #fff;
        }
        
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: white;
        }
        
        .receipt-header {
            text-align: center;
            border-bottom: 3px solid #2c5aa0;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .church-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 15px;
            background: #2c5aa0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }
        
        .church-name {
            font-size: 28px;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 5px;
        }
        
        .church-tagline {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .church-details {
            font-size: 12px;
            color: #666;
            line-height: 1.4;
        }
        
        .receipt-title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .member-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #2c5aa0;
        }
        
        .member-name {
            font-size: 20px;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 10px;
        }
        
        .member-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            font-size: 14px;
        }
        
        .period-info {
            text-align: center;
            background: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
            font-size: 16px;
            font-weight: bold;
            color: #1976d2;
        }
        
        .giving-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .summary-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #e9ecef;
        }
        
        .summary-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .summary-amount {
            font-size: 24px;
            font-weight: bold;
            color: #2c5aa0;
        }
        
        .transactions-section {
            margin-bottom: 30px;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 2px solid #e9ecef;
        }
        
        .transaction-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .transaction-table th {
            background: #2c5aa0;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
        }
        
        .transaction-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .transaction-table tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .amount {
            text-align: right;
            font-weight: bold;
            color: #2c5aa0;
        }
        
        .total-section {
            background: #2c5aa0;
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .total-label {
            font-size: 18px;
            margin-bottom: 10px;
        }
        
        .total-amount {
            font-size: 32px;
            font-weight: bold;
        }
        
        .receipt-footer {
            text-align: center;
            padding-top: 20px;
            border-top: 2px solid #e9ecef;
            color: #666;
            font-size: 12px;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #2c5aa0;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            z-index: 1000;
        }
        
        .print-button:hover {
            background: #1e3d6f;
        }
        
        .print-note {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #856404;
        }
        
        .print-note strong {
            display: block;
            margin-bottom: 5px;
            color: #856404;
        }
        
        .print-note ul {
            margin: 5px 0 0 20px;
            padding: 0;
        }
        
        .print-note li {
            margin: 3px 0;
        }
        
        /* Mobile Responsive Styles */
        @media screen and (max-width: 768px) {
            .receipt-container {
                padding: 15px;
            }
            
            .church-logo {
                width: 60px;
                height: 60px;
                font-size: 18px;
            }
            
            .church-name {
                font-size: 22px;
            }
            
            .church-tagline {
                font-size: 12px;
            }
            
            .church-details {
                font-size: 11px;
            }
            
            .receipt-title {
                font-size: 20px;
            }
            
            .member-info {
                padding: 15px;
            }
            
            .member-name {
                font-size: 18px;
            }
            
            .member-details {
                grid-template-columns: 1fr;
                gap: 8px;
                font-size: 13px;
            }
            
            .period-info {
                padding: 12px;
                font-size: 14px;
            }
            
            .giving-summary {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .summary-card {
                padding: 15px;
            }
            
            .summary-label {
                font-size: 12px;
            }
            
            .summary-amount {
                font-size: 20px;
            }
            
            .section-title {
                font-size: 16px;
            }
            
            .transaction-table {
                font-size: 11px;
                display: block;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            .transaction-table th,
            .transaction-table td {
                padding: 8px 4px;
                white-space: nowrap;
            }
            
            .total-section {
                padding: 15px;
            }
            
            .total-label {
                font-size: 16px;
            }
            
            .total-amount {
                font-size: 24px;
            }
            
            .print-button {
                top: 10px;
                right: 10px;
                padding: 10px 16px;
                font-size: 12px;
            }
        }
        
        @media screen and (max-width: 576px) {
            .receipt-container {
                padding: 10px;
            }
            
            .church-logo {
                width: 50px;
                height: 50px;
                font-size: 16px;
            }
            
            .church-name {
                font-size: 18px;
            }
            
            .receipt-title {
                font-size: 18px;
            }
            
            .member-name {
                font-size: 16px;
            }
            
            .member-details {
                font-size: 12px;
            }
            
            .period-info {
                font-size: 12px;
                padding: 10px;
            }
            
            .summary-amount {
                font-size: 18px;
            }
            
            .transaction-table {
                font-size: 10px;
            }
            
            .transaction-table th,
            .transaction-table td {
                padding: 6px 3px;
            }
            
            .total-amount {
                font-size: 20px;
            }
            
            .receipt-footer {
                font-size: 11px;
            }
        }
        
        /* Print Styles */
        @media print {
            /* Hide print button */
            .print-button,
            .print-note {
                display: none !important;
            }
            
            /* Remove all margins and padding */
            * {
                margin: 0;
                padding: 0;
            }
            
            body {
                margin: 0;
                padding: 0;
                background: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                color-adjust: exact;
            }
            
            .receipt-container {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 10px !important;
                width: 100% !important;
            }
            
            /* Optimize header for print */
            .receipt-header {
                padding-bottom: 10px !important;
                margin-bottom: 15px !important;
                border-bottom: 2px solid #2c5aa0 !important;
            }
            
            .church-logo {
                width: 60px !important;
                height: 60px !important;
                font-size: 18px !important;
            }
            
            .church-name {
                font-size: 20px !important;
                margin-bottom: 3px !important;
            }
            
            .church-tagline {
                font-size: 11px !important;
                margin-bottom: 5px !important;
            }
            
            .church-details {
                font-size: 10px !important;
            }
            
            /* Optimize receipt title */
            .receipt-title {
                font-size: 18px !important;
                margin-bottom: 15px !important;
            }
            
            /* Optimize member info */
            .member-info {
                padding: 10px !important;
                margin-bottom: 15px !important;
            }
            
            .member-name {
                font-size: 16px !important;
                margin-bottom: 8px !important;
            }
            
            .member-details {
                font-size: 11px !important;
                gap: 5px !important;
            }
            
            /* Optimize period info */
            .period-info {
                padding: 8px !important;
                margin-bottom: 15px !important;
                font-size: 12px !important;
            }
            
            /* Optimize summary cards */
            .giving-summary {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 10px !important;
                margin-bottom: 15px !important;
            }
            
            .summary-card {
                padding: 10px !important;
            }
            
            .summary-label {
                font-size: 10px !important;
                margin-bottom: 5px !important;
            }
            
            .summary-amount {
                font-size: 16px !important;
            }
            
            /* Optimize transaction sections */
            .transactions-section {
                margin-bottom: 15px !important;
                page-break-inside: avoid;
            }
            
            .section-title {
                font-size: 14px !important;
                margin-bottom: 8px !important;
            }
            
            .transaction-table {
                font-size: 9px !important;
                width: 100% !important;
                display: table !important;
            }
            
            .transaction-table th {
                padding: 6px 4px !important;
                font-size: 9px !important;
                background: #2c5aa0 !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .transaction-table td {
                padding: 5px 4px !important;
                font-size: 9px !important;
            }
            
            /* Optimize total section */
            .total-section {
                padding: 12px !important;
                margin-bottom: 15px !important;
                background: #2c5aa0 !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .total-label {
                font-size: 14px !important;
                margin-bottom: 5px !important;
            }
            
            .total-amount {
                font-size: 20px !important;
            }
            
            /* Optimize footer */
            .receipt-footer {
                padding-top: 10px !important;
                font-size: 9px !important;
                border-top: 1px solid #e9ecef !important;
            }
            
            /* Page setup - Remove browser headers and footers */
            @page {
                size: A4;
                margin: 0.5cm;
                /* Remove browser default headers and footers */
                margin-top: 0.5cm;
                margin-bottom: 0.5cm;
            }
            
            /* Hide browser print headers and footers using CSS Paged Media */
            @page {
                @top-left { content: ""; }
                @top-center { content: ""; }
                @top-right { content: ""; }
                @bottom-left { content: ""; }
                @bottom-center { content: ""; }
                @bottom-right { content: ""; }
            }
            
            /* Additional print margin adjustments */
            html, body {
                margin: 0 !important;
                padding: 0 !important;
            }
            
            /* Prevent page breaks inside important sections */
            .receipt-header,
            .member-info,
            .period-info,
            .total-section {
                page-break-inside: avoid;
            }
            
            /* Remove rounded corners for cleaner print */
            .member-info,
            .period-info,
            .summary-card,
            .total-section {
                border-radius: 0 !important;
            }
            
            /* Ensure colors print correctly */
            .amount {
                color: #2c5aa0 !important;
            }
            
            /* Stack summary cards if needed */
            @media print and (max-width: 21cm) {
                .giving-summary {
                    grid-template-columns: 1fr !important;
                }
            }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">
        <i class="fas fa-print"></i> Print Receipt
    </button>
    
    <div class="receipt-container">
        <!-- Print Instructions (hidden when printing) -->
        <div class="print-note">
            <strong>📄 Print Instructions:</strong>
            <ul>
                <li>Click the "Print Receipt" button above</li>
                <li>In the print dialog, look for "More settings" or "Options"</li>
                <li><strong>Uncheck "Headers and footers"</strong> to remove URL and page title from the top</li>
                <li>Click Print</li>
            </ul>
        </div>
        <!-- Receipt Header -->
        <div class="receipt-header">
            <div class="church-logo">WL</div>
            <div class="church-name">{{ $churchInfo['name'] }}</div>
            <div class="church-tagline">Connecting Hearts, Building Faith</div>
            <div class="church-details">
                {{ $churchInfo['address'] }}<br>
                Phone: {{ $churchInfo['phone'] }} | Email: {{ $churchInfo['email'] }}<br>
                Website: {{ $churchInfo['website'] }}
            </div>
        </div>
        
        <!-- Receipt Title -->
        <div class="receipt-title">Giving Receipt</div>
        
        <!-- Member Information -->
        <div class="member-info">
            <div class="member-name">{{ $member->full_name }}</div>
            <div class="member-details">
                <div><strong>Member ID:</strong> {{ $member->member_id }}</div>
                <div><strong>Phone:</strong> {{ $member->phone ?? 'N/A' }}</div>
                <div><strong>Email:</strong> {{ $member->email ?? 'N/A' }}</div>
                <div><strong>Address:</strong> {{ $member->address ?? 'N/A' }}</div>
            </div>
        </div>
        
        <!-- Period Information -->
        <div class="period-info">
            Giving Period: {{ \Carbon\Carbon::parse($startDate)->format('F d, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('F d, Y') }}
        </div>
        
        <!-- Giving Summary -->
        <div class="giving-summary">
            <div class="summary-card">
                <div class="summary-label">Total Tithes</div>
                <div class="summary-amount">TZS {{ number_format($totalTithes, 0) }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Total Offerings</div>
                <div class="summary-amount">TZS {{ number_format($totalOfferings, 0) }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Total Donations</div>
                <div class="summary-amount">TZS {{ number_format($totalDonations, 0) }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Total Pledged</div>
                <div class="summary-amount">TZS {{ number_format($totalPledged, 0) }}</div>
            </div>
        </div>
        
        <!-- Detailed Transactions -->
        @if($tithes->count() > 0)
        <div class="transactions-section">
            <div class="section-title">Tithes</div>
            <table class="transaction-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Reference</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tithes as $tithe)
                    <tr>
                        <td>{{ $tithe->tithe_date ? \Carbon\Carbon::parse($tithe->tithe_date)->format('M d, Y') : '-' }}</td>
                        <td class="amount">TZS {{ number_format($tithe->amount, 0) }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $tithe->payment_method)) }}</td>
                        <td>{{ $tithe->reference_number ?? 'N/A' }}</td>
                        <td>
                            <span style="color: {{ $tithe->is_verified ? '#28a745' : '#ffc107' }}">
                                {{ $tithe->is_verified ? 'Verified' : 'Pending' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        
        @if($offerings->count() > 0)
        <div class="transactions-section">
            <div class="section-title">Offerings</div>
            <table class="transaction-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Reference</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($offerings as $offering)
                    <tr>
                        <td>{{ $offering->offering_date ? \Carbon\Carbon::parse($offering->offering_date)->format('M d, Y') : '-' }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $offering->offering_type)) }}</td>
                        <td class="amount">TZS {{ number_format($offering->amount, 0) }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $offering->payment_method)) }}</td>
                        <td>{{ $offering->reference_number ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        
        @if($donations->count() > 0)
        <div class="transactions-section">
            <div class="section-title">Donations</div>
            <table class="transaction-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Purpose</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Reference</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($donations as $donation)
                    <tr>
                        <td>{{ $donation->donation_date ? \Carbon\Carbon::parse($donation->donation_date)->format('M d, Y') : '-' }}</td>
                        <td>{{ $donation->purpose ?? 'General' }}</td>
                        <td class="amount">TZS {{ number_format($donation->amount, 0) }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $donation->payment_method)) }}</td>
                        <td>{{ $donation->reference_number ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        
        @if($pledges->count() > 0)
        <div class="transactions-section">
            <div class="section-title">Pledges</div>
            <table class="transaction-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Pledged</th>
                        <th>Paid</th>
                        <th>Balance</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pledges as $pledge)
                    <tr>
                        <td>{{ $pledge->pledge_date ? \Carbon\Carbon::parse($pledge->pledge_date)->format('M d, Y') : '-' }}</td>
                        <td>{{ ucfirst($pledge->pledge_type) }}</td>
                        <td class="amount">TZS {{ number_format($pledge->pledge_amount, 0) }}</td>
                        <td class="amount">TZS {{ number_format($pledge->amount_paid, 0) }}</td>
                        <td class="amount">TZS {{ number_format($pledge->pledge_amount - $pledge->amount_paid, 0) }}</td>
                        <td>
                            <span style="color: {{ $pledge->status == 'completed' ? '#28a745' : ($pledge->status == 'overdue' ? '#dc3545' : '#ffc107') }}">
                                {{ ucfirst($pledge->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        
        <!-- Total Summary -->
        <div class="total-section">
            <div class="total-label">Total Giving for Period</div>
            <div class="total-amount">TZS {{ number_format($totalGiving, 0) }}</div>
        </div>
        
        <!-- Receipt Footer -->
        <div class="receipt-footer">
            <p><strong>Thank you for your faithful giving!</strong></p>
            <p>This receipt serves as official documentation of your contributions to {{ $churchInfo['name'] }}.</p>
            <p>Generated on {{ now()->format('F d, Y \a\t g:i A') }}</p>
            <p>For questions about this receipt, please contact us at {{ $churchInfo['email'] }}</p>
        </div>
    </div>
    
    <script>
        // Print function - CSS will handle removing headers/footers where supported
        // Note: Users may need to disable "Headers and footers" in browser print settings
        function printReceipt() {
            window.print();
        }
        
        // Update print button onclick
        document.addEventListener('DOMContentLoaded', function() {
            const printBtn = document.querySelector('.print-button');
            if (printBtn) {
                printBtn.onclick = printReceipt;
            }
        });
    </script>
</body>
</html>






