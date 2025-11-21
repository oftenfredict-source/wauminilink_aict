<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leadership Report - {{ date('F d, Y') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }
        .summary {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
        }
        .summary-item {
            text-align: center;
        }
        .summary-item h3 {
            margin: 0;
            color: #007bff;
            font-size: 24px;
        }
        .summary-item p {
            margin: 5px 0 0 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .position-section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        .position-header {
            background-color: #007bff;
            color: white;
            padding: 10px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .status-active {
            color: #28a745;
            font-weight: bold;
        }
        .status-inactive {
            color: #6c757d;
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Waumini Link - Leadership Report</h1>
        <p>Generated on {{ date('F d, Y \a\t g:i A') }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <h3>{{ $leaders->count() }}</h3>
            <p>Total Positions</p>
        </div>
        <div class="summary-item">
            <h3>{{ $activeLeaders->count() }}</h3>
            <p>Active Positions</p>
        </div>
        <div class="summary-item">
            <h3>{{ $leaders->count() - $activeLeaders->count() }}</h3>
            <p>Inactive Positions</p>
        </div>
        <div class="summary-item">
            <h3>{{ $leadersByPosition->count() }}</h3>
            <p>Position Types</p>
        </div>
    </div>

    @foreach($leadersByPosition as $position => $positionLeaders)
        <div class="position-section">
            <div class="position-header">
                {{ $positionLeaders->first()->position_display }} ({{ $positionLeaders->count() }} {{ Str::plural('position', $positionLeaders->count()) }})
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Member Name</th>
                        <th>Member ID</th>
                        <th>Position Title</th>
                        <th>Appointment Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Appointed By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($positionLeaders as $leader)
                        <tr>
                            <td>{{ $leader->member->full_name }}</td>
                            <td>{{ $leader->member->member_id }}</td>
                            <td>{{ $leader->position_title ?? 'N/A' }}</td>
                            <td>{{ $leader->appointment_date->format('M d, Y') }}</td>
                            <td>{{ $leader->end_date ? $leader->end_date->format('M d, Y') : 'Indefinite' }}</td>
                            <td class="{{ $leader->is_active ? 'status-active' : 'status-inactive' }}">
                                {{ $leader->is_active ? 'Active' : 'Inactive' }}
                            </td>
                            <td>{{ $leader->appointed_by ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

    <div class="footer">
        <p>This report was generated by Waumini Link Church Management System</p>
        <p>For questions or concerns, please contact the church administration</p>
    </div>

    <script>
        // Auto-print when page loads
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>




















