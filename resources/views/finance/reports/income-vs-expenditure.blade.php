@extends('layouts.index')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4"><i class="fas fa-chart-line me-2"></i>Income vs Expenditure Report</h1>
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
            <form method="GET" action="{{ route('reports.income-vs-expenditure') }}">
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
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="small text-white-50">Total Income</div>
                            <div class="h4">TZS {{ number_format($totalIncome, 0) }}</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-arrow-up fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="small text-white-50">Total Expenses</div>
                            <div class="h4">TZS {{ number_format($totalExpenses, 0) }}</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-arrow-down fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card {{ $netIncome >= 0 ? 'bg-primary' : 'bg-warning' }} text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="small text-white-50">Net Income</div>
                            <div class="h4">TZS {{ number_format($netIncome, 0) }}</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-chart-line fa-2x"></i>
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
                            <div class="small text-white-50">Profit Margin</div>
                            <div class="h4">{{ $totalIncome > 0 ? number_format(($netIncome / $totalIncome) * 100, 1) : 0 }}%</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-percentage fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Income Breakdown -->
    <div class="row mb-4">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>Income Sources
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Source</th>
                                    <th>Amount</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><i class="fas fa-coins text-primary me-2"></i>Tithes</td>
                                    <td class="text-end">TZS {{ number_format($tithes, 0) }}</td>
                                    <td class="text-end">{{ $totalIncome > 0 ? number_format(($tithes / $totalIncome) * 100, 1) : 0 }}%</td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-gift text-success me-2"></i>Offerings</td>
                                    <td class="text-end">TZS {{ number_format($offerings, 0) }}</td>
                                    <td class="text-end">{{ $totalIncome > 0 ? number_format(($offerings / $totalIncome) * 100, 1) : 0 }}%</td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-heart text-info me-2"></i>Donations</td>
                                    <td class="text-end">TZS {{ number_format($donations, 0) }}</td>
                                    <td class="text-end">{{ $totalIncome > 0 ? number_format(($donations / $totalIncome) * 100, 1) : 0 }}%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>Expenses by Category
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Amount</th>
                                    <th>Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($expensesByCategory as $category => $data)
                                <tr>
                                    <td><span class="badge bg-secondary">{{ ucfirst($category) }}</span></td>
                                    <td class="text-end">TZS {{ number_format($data['total'], 0) }}</td>
                                    <td class="text-center">{{ $data['count'] }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">No expense data found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Trend Chart -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-chart-line me-1"></i>Monthly Income vs Expenditure Trend
        </div>
        <div class="card-body">
            <canvas id="monthlyTrendChart" width="100%" height="50"></canvas>
        </div>
    </div>

    <!-- Income vs Expenditure Pie Chart -->
    <div class="row mb-4">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>Income Distribution
                </div>
                <div class="card-body">
                    <canvas id="incomeChart" width="100%" height="50"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>Expense Distribution
                </div>
                <div class="card-body">
                    <canvas id="expenseChart" width="100%" height="50"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Health Indicators -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-heartbeat me-1"></i>Financial Health Indicators
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="text-center">
                        <h5 class="text-primary">Expense Ratio</h5>
                        <div class="h3 {{ ($totalExpenses / max($totalIncome, 1)) <= 0.8 ? 'text-success' : (($totalExpenses / max($totalIncome, 1)) <= 0.95 ? 'text-warning' : 'text-danger') }}">
                            {{ number_format(($totalExpenses / max($totalIncome, 1)) * 100, 1) }}%
                        </div>
                        <small class="text-muted">Expenses as % of Income</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <h5 class="text-primary">Savings Rate</h5>
                        <div class="h3 {{ $netIncome >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ $netIncome >= 0 ? number_format(($netIncome / max($totalIncome, 1)) * 100, 1) : 0 }}%
                        </div>
                        <small class="text-muted">Net Income as % of Total Income</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <h5 class="text-primary">Financial Status</h5>
                        <div class="h3 {{ $netIncome >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ $netIncome >= 0 ? 'Healthy' : 'Deficit' }}
                        </div>
                        <small class="text-muted">Overall Financial Position</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Trend Chart
    const trendCtx = document.getElementById('monthlyTrendChart').getContext('2d');
    const monthlyData = @json($monthlyData);
    
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: monthlyData.map(item => item.month),
            datasets: [{
                label: 'Income',
                data: monthlyData.map(item => item.income),
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1,
                fill: false
            }, {
                label: 'Expenses',
                data: monthlyData.map(item => item.expenses),
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.1,
                fill: false
            }, {
                label: 'Net Income',
                data: monthlyData.map(item => item.net),
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                tension: 0.1,
                fill: false
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

    // Income Chart
    const incomeCtx = document.getElementById('incomeChart').getContext('2d');
    new Chart(incomeCtx, {
        type: 'doughnut',
        data: {
            labels: ['Tithes', 'Offerings', 'Donations'],
            datasets: [{
                data: [{{ $tithes }}, {{ $offerings }}, {{ $donations }}],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(255, 206, 86, 0.8)'
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

    // Expense Chart
    const expenseCtx = document.getElementById('expenseChart').getContext('2d');
    const expenseData = @json($expensesByCategory);
    
    new Chart(expenseCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(expenseData),
            datasets: [{
                data: Object.values(expenseData).map(item => item.total),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
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
});
</script>

<script>
function exportReport(format) {
    const startDate = '{{ $startDate }}';
    const endDate = '{{ $endDate }}';
    
    const url = `/reports/export/${format}?report_type=income-vs-expenditure&start_date=${startDate}&end_date=${endDate}`;
    
    if (format === 'pdf') {
        window.open(url, '_blank');
    } else {
        window.location.href = url;
    }
}
</script>
@endsection

