@extends('layouts.index')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4"><i class="fas fa-wallet me-2"></i>Budget Performance Report</h1>
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
            <form method="GET" action="{{ route('reports.budget-performance') }}">
                <div class="row">
                    <div class="col-md-4">
                        <label for="budget_id" class="form-label">Select Budget</label>
                        <select class="form-select" id="budget_id" name="budget_id">
                            <option value="">All Budgets</option>
                            @foreach($budgets as $b)
                                <option value="{{ $b->id }}" {{ request('budget_id') == $b->id ? 'selected' : '' }}>
                                    {{ $b->budget_name }} ({{ $b->fiscal_year }})
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
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Generate Report</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($budget)
    <!-- Budget Summary -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="small text-white-50">Total Budget</div>
                            <div class="h4">TZS {{ number_format($budget->total_budget, 0) }}</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-wallet fa-2x"></i>
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
                            <div class="small text-white-50">Amount Spent</div>
                            <div class="h4">TZS {{ number_format($budget->spent_amount, 0) }}</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-receipt fa-2x"></i>
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
                            <div class="small text-white-50">Remaining</div>
                            <div class="h4">TZS {{ number_format($budget->remaining_amount, 0) }}</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-piggy-bank fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card {{ $budget->is_over_budget ? 'bg-danger' : ($budget->is_near_limit ? 'bg-warning' : 'bg-success') }} text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="small text-white-50">Utilization</div>
                            <div class="h4">{{ $budget->utilization_percentage }}%</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-chart-pie fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Budget Information -->
    <div class="card mb-4">
        <div class="card-header report-header-primary py-2">
            <h6 class="mb-0 text-white"><i class="fas fa-info-circle me-1"></i>Budget Information</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>{{ $budget->budget_name }}</h5>
                    <p class="text-muted">Type: {{ ucfirst($budget->budget_type) }}</p>
                    <p class="text-muted">Fiscal Year: {{ $budget->fiscal_year }}</p>
                    <p class="text-muted">Period: {{ $budget->start_date->format('M d, Y') }} - {{ $budget->end_date->format('M d, Y') }}</p>
                </div>
                <div class="col-md-6">
                    <h6>Budget Status</h6>
                    <div class="progress mb-2" style="height: 25px;">
                        <div class="progress-bar {{ $budget->is_over_budget ? 'bg-danger' : ($budget->is_near_limit ? 'bg-warning' : 'bg-success') }}" 
                             style="width: {{ min($budget->utilization_percentage, 100) }}%">
                            {{ $budget->utilization_percentage }}%
                        </div>
                    </div>
                    <p class="text-muted">
                        @if($budget->is_over_budget)
                            <i class="fas fa-exclamation-triangle text-danger me-1"></i>Over Budget
                        @elseif($budget->is_near_limit)
                            <i class="fas fa-exclamation-circle text-warning me-1"></i>Near Limit
                        @else
                            <i class="fas fa-check-circle text-success me-1"></i>On Track
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Performance Chart -->
    <div class="card mb-4">
        <div class="card-header report-header-info py-2">
            <h6 class="mb-0 text-white"><i class="fas fa-chart-line me-1"></i>Monthly Budget Performance</h6>
        </div>
        <div class="card-body">
            <canvas id="monthlyChart" width="100%" height="50"></canvas>
        </div>
    </div>

    <!-- Expenses by Category -->
    <div class="card mb-4">
        <div class="card-header report-header-warning py-2">
            <h6 class="mb-0 text-white"><i class="fas fa-chart-bar me-1"></i>Expenses by Category</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="categoryTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Total Amount</th>
                            <th>Transaction Count</th>
                            <th>Average Amount</th>
                            <th>Percentage of Budget</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expensesByCategory as $category => $data)
                        <tr>
                            <td>
                                <span class="badge bg-secondary">{{ ucfirst($category) }}</span>
                            </td>
                            <td class="text-end">TZS {{ number_format($data['total'], 0) }}</td>
                            <td class="text-center">{{ $data['count'] }}</td>
                            <td class="text-end">TZS {{ number_format($data['avg'], 0) }}</td>
                            <td class="text-end">{{ $budget->total_budget > 0 ? number_format(($data['total'] / $budget->total_budget) * 100, 1) : 0 }}%</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No expense data found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Expenses -->
    <div class="card mb-4">
        <div class="card-header report-header-neutral py-2">
            <h6 class="mb-0 text-white"><i class="fas fa-receipt me-1"></i>Recent Expenses</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="expensesTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Expense Name</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Vendor</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $expense)
                        <tr>
                            <td>{{ $expense->expense_date->format('M d, Y') }}</td>
                            <td>{{ $expense->expense_name }}</td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($expense->expense_category) }}</span>
                            </td>
                            <td class="text-end">TZS {{ number_format($expense->amount, 0) }}</td>
                            <td>{{ $expense->vendor ?? '-' }}</td>
                            <td>
                                @if($expense->status == 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @elseif($expense->status == 'approved')
                                    <span class="badge bg-primary">Approved</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No expenses found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <!-- Budget Selection -->
    <div class="card mb-4">
        <div class="card-header report-header-primary py-2">
            <h6 class="mb-0 text-white"><i class="fas fa-wallet me-1"></i>Select a Budget to View Performance</h6>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($budgets as $b)
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $b->budget_name }}</h5>
                            <p class="card-text text-muted">{{ $b->budget_type }} - {{ $b->fiscal_year }}</p>
                            <div class="progress mb-2" style="height: 20px;">
                                <div class="progress-bar {{ $b->is_over_budget ? 'bg-danger' : ($b->is_near_limit ? 'bg-warning' : 'bg-success') }}" 
                                     style="width: {{ min($b->utilization_percentage, 100) }}%">
                                    {{ $b->utilization_percentage }}%
                                </div>
                            </div>
                            <p class="card-text">
                                <small class="text-muted">
                                    TZS {{ number_format($b->spent_amount, 0) }} / {{ number_format($b->total_budget, 0) }}
                                </small>
                            </p>
                            <a href="{{ route('reports.budget-performance', ['budget_id' => $b->id]) }}" class="btn btn-primary">View Performance</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

@if($budget && isset($monthlyData))
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyData = @json($monthlyData);
    const budgetTotal = {{ $budget->total_budget }};
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: monthlyData.map(item => item.month),
            datasets: [{
                label: 'Amount Spent',
                data: monthlyData.map(item => item.spent),
                backgroundColor: 'rgba(255, 99, 132, 0.8)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }, {
                label: 'Budget Limit',
                data: monthlyData.map(item => item.budget),
                backgroundColor: 'rgba(54, 162, 235, 0.8)',
                borderColor: 'rgba(54, 162, 235, 1)',
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
function exportReport(format) {
    const budgetId = '{{ $budget->id ?? "" }}';
    const startDate = '{{ $startDate }}';
    const endDate = '{{ $endDate }}';
    
    const url = `/reports/export/${format}?report_type=budget-performance&budget_id=${budgetId}&start_date=${startDate}&end_date=${endDate}`;
    
    if (format === 'pdf') {
        window.open(url, '_blank');
    } else {
        window.location.href = url;
    }
}
</script>
@endsection

<style>
.report-header-primary{
    background: linear-gradient(135deg, #4e73df 0%, #6f42c1 100%) !important;
}
.report-header-info{
    background: linear-gradient(135deg, #36b9cc 0%, #2aa2b3 100%) !important;
}
.report-header-warning{
    background: linear-gradient(135deg, #f6c23e 0%, #d6a62f 100%) !important;
}
.report-header-neutral{
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%) !important;
}
.report-header-primary, .report-header-info, .report-header-warning, .report-header-neutral{
    color: #fff !important;
}
.report-header-primary h6, .report-header-info h6, .report-header-warning h6, .report-header-neutral h6{
    color: #fff !important;
}
</style>
