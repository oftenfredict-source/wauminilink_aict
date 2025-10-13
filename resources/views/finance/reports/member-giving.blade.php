@extends('layouts.index')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4"><i class="fas fa-user-chart me-2"></i>Member Giving Report</h1>
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-success" onclick="exportReport('pdf')">
                <i class="fas fa-file-pdf me-1"></i>Export PDF
            </button>
            <button type="button" class="btn btn-primary" onclick="exportReport('excel')">
                <i class="fas fa-file-excel me-1"></i>Export Excel
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-filter me-1"></i>Report Filters
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.member-giving') }}">
                <div class="row">
                    <div class="col-md-4">
                        <label for="member_id" class="form-label">Select Member</label>
                        <select class="form-select select2-member" id="member_id" name="member_id">
                            <option value="">All Members</option>
                            @foreach($members as $m)
                                <option value="{{ $m->id }}" {{ request('member_id') == $m->id ? 'selected' : '' }}>
                                    {{ $m->full_name }} ({{ $m->member_id }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date', date('Y-01-01')) }}">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date', date('Y-12-31')) }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Generate Report</button>
                            @if($member)
                                <a href="{{ route('reports.member-receipt', $member->id) }}?start_date={{ request('start_date', date('Y-01-01')) }}&end_date={{ request('end_date', date('Y-12-31')) }}" 
                                   class="btn btn-success" target="_blank">
                                    <i class="fas fa-receipt me-1"></i>Generate Receipt
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($member)
    <!-- Member Summary -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="small text-white-50">Total Tithes</div>
                            <div class="h4">TZS {{ number_format($totalTithes, 0) }}</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-coins fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="small text-white-50">Total Offerings</div>
                            <div class="h4">TZS {{ number_format($totalOfferings, 0) }}</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-gift fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="small text-white-50">Total Donations</div>
                            <div class="h4">TZS {{ number_format($totalDonations, 0) }}</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-heart fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="small text-white-50">Total Giving</div>
                            <div class="h4">TZS {{ number_format($totalGiving, 0) }}</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Member Information -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-user me-1"></i>Member Information
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>{{ $member->full_name }}</h5>
                    <p class="text-muted">Member ID: {{ $member->member_id }}</p>
                    <p class="text-muted">Phone: {{ $member->phone_number }}</p>
                </div>
                <div class="col-md-6">
                    <h6>Pledge Information</h6>
                    <p class="text-muted">Total Pledged: TZS {{ number_format($totalPledged, 0) }}</p>
                    <p class="text-muted">Total Paid: TZS {{ number_format($totalPaid, 0) }}</p>
                    <p class="text-muted">Remaining: TZS {{ number_format($totalPledged - $totalPaid, 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Breakdown Chart -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-chart-bar me-1"></i>Monthly Giving Breakdown
        </div>
        <div class="card-body">
            <canvas id="monthlyChart" width="100%" height="50"></canvas>
        </div>
    </div>

    <!-- Detailed Transactions -->
    <div class="row mb-4">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-coins me-1"></i>Tithes
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tithes as $tithe)
                                <tr>
                                    <td>{{ $tithe->tithe_date->format('M d, Y') }}</td>
                                    <td>TZS {{ number_format($tithe->amount, 0) }}</td>
                                    <td>{{ ucfirst($tithe->payment_method) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">No tithes found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-gift me-1"></i>Offerings
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($offerings as $offering)
                                <tr>
                                    <td>{{ $offering->offering_date->format('M d, Y') }}</td>
                                    <td>TZS {{ number_format($offering->amount, 0) }}</td>
                                    <td>{{ ucfirst($offering->offering_type) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">No offerings found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-heart me-1"></i>Donations
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($donations as $donation)
                                <tr>
                                    <td>{{ $donation->donation_date->format('M d, Y') }}</td>
                                    <td>TZS {{ number_format($donation->amount, 0) }}</td>
                                    <td>{{ ucfirst($donation->donation_type) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">No donations found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-handshake me-1"></i>Pledges
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Pledged</th>
                                    <th>Paid</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pledges as $pledge)
                                <tr>
                                    <td>{{ $pledge->pledge_date->format('M d, Y') }}</td>
                                    <td>TZS {{ number_format($pledge->pledge_amount, 0) }}</td>
                                    <td>TZS {{ number_format($pledge->amount_paid, 0) }}</td>
                                    <td>
                                        @if($pledge->status == 'completed')
                                            <span class="badge bg-success">Completed</span>
                                        @elseif($pledge->status == 'overdue')
                                            <span class="badge bg-danger">Overdue</span>
                                        @else
                                            <span class="badge bg-primary">Active</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No pledges found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Member Selection -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-users me-1"></i>Select a Member to View Report
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($members as $m)
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-user fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">{{ $m->full_name }}</h5>
                            <p class="card-text text-muted">Member ID: {{ $m->member_id }}</p>
                            <a href="{{ route('reports.member-giving', ['member_id' => $m->id]) }}" class="btn btn-primary">View Report</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

@if($member && isset($monthlyData))
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 for member dropdown
    $('.select2-member').select2({
        placeholder: 'Search for a member...',
        allowClear: true,
        width: '100%'
    });
    const ctx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyData = @json($monthlyData);
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: monthlyData.map(item => item.month),
            datasets: [{
                label: 'Tithes',
                data: monthlyData.map(item => item.tithes),
                backgroundColor: 'rgba(54, 162, 235, 0.8)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }, {
                label: 'Offerings',
                data: monthlyData.map(item => item.offerings),
                backgroundColor: 'rgba(75, 192, 192, 0.8)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }, {
                label: 'Donations',
                data: monthlyData.map(item => item.donations),
                backgroundColor: 'rgba(255, 206, 86, 0.8)',
                borderColor: 'rgba(255, 206, 86, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'TZS ' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': TZS ' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            }
        }
    });
});
</script>
@endif

<script>
// Initialize Select2 for member dropdown (when no member is selected)
document.addEventListener('DOMContentLoaded', function() {
    $('.select2-member').select2({
        placeholder: 'Search for a member...',
        allowClear: true,
        width: '100%'
    });
});

function exportReport(format) {
    const memberId = '{{ $member->id ?? "" }}';
    const startDate = '{{ $startDate }}';
    const endDate = '{{ $endDate }}';
    
    const url = `/reports/export/${format}?report_type=member-giving&member_id=${memberId}&start_date=${startDate}&end_date=${endDate}`;
    
    if (format === 'pdf') {
        window.open(url, '_blank');
    } else {
        window.location.href = url;
    }
}
</script>
@endsection
