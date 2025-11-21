@extends('layouts.index')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4"><i class="fas fa-building me-2"></i>Department Giving Report</h1>
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
        <div class="card-header report-header-neutral py-2">
            <h6 class="mb-0 text-white"><i class="fas fa-filter me-1"></i>Report Filters</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.department-giving') }}">
                <div class="row">
                    <div class="col-md-4">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date', date('Y-01-01')) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date', date('Y-12-31')) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Generate Report</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="small text-white-50">Total Offerings</div>
                            <div class="h4">TZS {{ number_format($offeringTypes->sum('total_amount'), 0) }}</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-gift fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="small text-white-50">Total Donations</div>
                            <div class="h4">TZS {{ number_format($donationTypes->sum('total_amount'), 0) }}</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-heart fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="small text-white-50">Total Pledged</div>
                            <div class="h4">TZS {{ number_format($pledgeTypes->sum('total_pledged'), 0) }}</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-handshake fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Combined by Purpose (Pledges + Offerings + Donations) -->
    @if(isset($combinedByPurpose) && !empty($combinedByPurpose))
    <div class="card mb-4">
        <div class="card-header report-header-warning py-2">
            <h6 class="mb-0 text-white"><i class="fas fa-layer-group me-1"></i>Combined Giving by Purpose</h6>
            <small class="text-white-50">This section combines Pledges, Offerings, and Donations that share the same purpose</small>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="combinedTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Purpose</th>
                            <th>Pledges (Paid)</th>
                            <th>Offerings</th>
                            <th>Donations</th>
                            <th>Combined Total</th>
                            <th>Total Pledged</th>
                            <th>Outstanding</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $grandTotal = 0;
                            $grandPledged = 0;
                        @endphp
                        @foreach($combinedByPurpose as $purpose => $data)
                        @php
                            $grandTotal += $data['combined_total'];
                            $grandPledged += $data['combined_pledged'];
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $data['display_name'] }}</strong>
                                <br>
                                <small class="text-muted">
                                    {{ $data['pledges']['count'] }} pledges, 
                                    {{ $data['offerings']['count'] }} offerings, 
                                    {{ $data['donations']['count'] }} donations
                                </small>
                            </td>
                            <td class="text-end">
                                TZS {{ number_format($data['pledges']['total_paid'], 0) }}
                                <br>
                                <small class="text-muted">of {{ number_format($data['pledges']['total_pledged'], 0) }} pledged</small>
                            </td>
                            <td class="text-end">TZS {{ number_format($data['offerings']['total'], 0) }}</td>
                            <td class="text-end">TZS {{ number_format($data['donations']['total'], 0) }}</td>
                            <td class="text-end">
                                <strong>TZS {{ number_format($data['combined_total'], 0) }}</strong>
                            </td>
                            <td class="text-end">TZS {{ number_format($data['combined_pledged'], 0) }}</td>
                            <td class="text-end">
                                <span class="badge bg-{{ $data['pledges']['outstanding'] > 0 ? 'warning' : 'success' }}">
                                    TZS {{ number_format($data['pledges']['outstanding'], 0) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-primary">
                            <th>Grand Total</th>
                            <th class="text-end">TZS {{ number_format(collect($combinedByPurpose)->sum('pledges.total_paid'), 0) }}</th>
                            <th class="text-end">TZS {{ number_format(collect($combinedByPurpose)->sum('offerings.total'), 0) }}</th>
                            <th class="text-end">TZS {{ number_format(collect($combinedByPurpose)->sum('donations.total'), 0) }}</th>
                            <th class="text-end">TZS {{ number_format($grandTotal, 0) }}</th>
                            <th class="text-end">TZS {{ number_format($grandPledged, 0) }}</th>
                            <th class="text-end">TZS {{ number_format(collect($combinedByPurpose)->sum('pledges.outstanding'), 0) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Offering Types -->
    <div class="card mb-4">
        <div class="card-header report-header-primary py-2">
            <h6 class="mb-0 text-white"><i class="fas fa-gift me-1"></i>Offering Types Breakdown</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="offeringTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Offering Type</th>
                            <th>Total Amount</th>
                            <th>Transaction Count</th>
                            <th>Average per Transaction</th>
                            <th>Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalOfferings = $offeringTypes->sum('total_amount');
                        @endphp
                        @forelse($offeringTypes as $offering)
                        <tr>
                            <td>
                                <span class="badge bg-info">
                                    @if($offering->offering_type == 'general')
                                        General Offering
                                    @elseif(in_array($offering->offering_type, ['special', 'thanksgiving', 'building_fund']))
                                        {{ ucfirst(str_replace('_', ' ', $offering->offering_type)) }}
                                    @else
                                        {{ ucfirst($offering->offering_type) }}
                                    @endif
                                </span>
                            </td>
                            <td class="text-end">TZS {{ number_format($offering->total_amount, 0) }}</td>
                            <td class="text-center">{{ $offering->transaction_count }}</td>
                            <td class="text-end">TZS {{ number_format($offering->total_amount / max($offering->transaction_count, 1), 0) }}</td>
                            <td class="text-end">{{ $totalOfferings > 0 ? number_format(($offering->total_amount / $totalOfferings) * 100, 1) : 0 }}%</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No offering data found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Donation Types -->
    <div class="card mb-4">
        <div class="card-header report-header-success py-2">
            <h6 class="mb-0 text-white"><i class="fas fa-heart me-1"></i>Donation Types Breakdown</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="donationTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Donation Type</th>
                            <th>Total Amount</th>
                            <th>Transaction Count</th>
                            <th>Average per Transaction</th>
                            <th>Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalDonations = $donationTypes->sum('total_amount');
                        @endphp
                        @forelse($donationTypes as $donation)
                        <tr>
                            <td>
                                <span class="badge bg-success">{{ ucfirst($donation->donation_type) }}</span>
                            </td>
                            <td class="text-end">TZS {{ number_format($donation->total_amount, 0) }}</td>
                            <td class="text-center">{{ $donation->transaction_count }}</td>
                            <td class="text-end">TZS {{ number_format($donation->total_amount / max($donation->transaction_count, 1), 0) }}</td>
                            <td class="text-end">{{ $totalDonations > 0 ? number_format(($donation->total_amount / $totalDonations) * 100, 1) : 0 }}%</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No donation data found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pledge Types -->
    <div class="card mb-4">
        <div class="card-header report-header-info py-2">
            <h6 class="mb-0 text-white"><i class="fas fa-handshake me-1"></i>Pledge Types Breakdown</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="pledgeTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Pledge Type</th>
                            <th>Total Pledged</th>
                            <th>Total Paid</th>
                            <th>Remaining</th>
                            <th>Pledge Count</th>
                            <th>Completion Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pledgeTypes as $pledge)
                        <tr>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($pledge->pledge_type) }}</span>
                            </td>
                            <td class="text-end">TZS {{ number_format($pledge->total_pledged, 0) }}</td>
                            <td class="text-end">TZS {{ number_format($pledge->total_paid, 0) }}</td>
                            <td class="text-end">TZS {{ number_format($pledge->total_pledged - $pledge->total_paid, 0) }}</td>
                            <td class="text-center">{{ $pledge->pledge_count }}</td>
                            <td class="text-end">
                                @php
                                    $completionRate = $pledge->total_pledged > 0 ? ($pledge->total_paid / $pledge->total_pledged) * 100 : 0;
                                @endphp
                                <span class="badge {{ $completionRate >= 100 ? 'bg-success' : ($completionRate >= 75 ? 'bg-warning' : 'bg-danger') }}">
                                    {{ number_format($completionRate, 1) }}%
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No pledge data found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row mb-4">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header report-header-primary py-2">
                    <h6 class="mb-0 text-white"><i class="fas fa-chart-pie me-1"></i>Offering Types Distribution</h6>
                </div>
                <div class="card-body">
                    <canvas id="offeringChart" width="100%" height="50"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header report-header-success py-2">
                    <h6 class="mb-0 text-white"><i class="fas fa-chart-pie me-1"></i>Donation Types Distribution</h6>
                </div>
                <div class="card-body">
                    <canvas id="donationChart" width="100%" height="50"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Offering Types Chart
    const offeringCtx = document.getElementById('offeringChart').getContext('2d');
    const offeringData = @json($offeringTypes);
    
    new Chart(offeringCtx, {
        type: 'doughnut',
        data: {
            labels: offeringData.map(item => {
                if (item.offering_type === 'general') {
                    return 'General Offering';
                } else if (['special', 'thanksgiving', 'building_fund'].includes(item.offering_type)) {
                    return item.offering_type.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
                } else {
                    return item.offering_type.charAt(0).toUpperCase() + item.offering_type.slice(1);
                }
            }),
            datasets: [{
                data: offeringData.map(item => item.total_amount),
                backgroundColor: [
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(153, 102, 255, 0.8)',
                    'rgba(255, 159, 64, 0.8)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': TZS ' + context.parsed.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Donation Types Chart
    const donationCtx = document.getElementById('donationChart').getContext('2d');
    const donationData = @json($donationTypes);
    
    new Chart(donationCtx, {
        type: 'doughnut',
        data: {
            labels: donationData.map(item => item.donation_type),
            datasets: [{
                data: donationData.map(item => item.total_amount),
                backgroundColor: [
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(153, 102, 255, 0.8)',
                    'rgba(255, 159, 64, 0.8)',
                    'rgba(54, 162, 235, 0.8)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': TZS ' + context.parsed.toLocaleString();
                        }
                    }
                }
            }
        }
    });
});
</script>

<script>
function exportReport(format) {
    const startDate = '{{ $startDate }}';
    const endDate = '{{ $endDate }}';
    
    const url = `/reports/export/${format}?report_type=department-giving&start_date=${startDate}&end_date=${endDate}`;
    
    if (format === 'pdf') {
        window.open(url, '_blank');
    } else {
        window.location.href = url;
    }
}
</script>
@endsection
<style>
.report-header-primary{ background: linear-gradient(135deg, #4e73df 0%, #6f42c1 100%) !important; color:#fff !important; }
.report-header-success{ background: linear-gradient(135deg, #1cc88a 0%, #16a36f 100%) !important; color:#fff !important; }
.report-header-info{ background: linear-gradient(135deg, #36b9cc 0%, #2aa2b3 100%) !important; color:#fff !important; }
.report-header-neutral{ background: linear-gradient(135deg, #6c757d 0%, #495057 100%) !important; color:#fff !important; }
.report-header-primary h6, .report-header-success h6, .report-header-info h6, .report-header-neutral h6{ color:#fff !important; }
</style>











