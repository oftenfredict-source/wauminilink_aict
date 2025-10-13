@extends('layouts.index')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4"><i class="fas fa-chart-pie me-2"></i>Financial Reports</h1>
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-success" onclick="exportReport('pdf')">
                <i class="fas fa-file-pdf me-1"></i>Export PDF
            </button>
            <button type="button" class="btn btn-primary" onclick="exportReport('excel')">
                <i class="fas fa-file-excel me-1"></i>Export Excel
            </button>
        </div>
    </div>

    <!-- Report Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="small text-white-50">Member Giving Report</div>
                            <div class="h6">Individual Member Analysis</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-chart fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white text-decoration-none" href="{{ route('reports.member-giving') }}">
                        View Report
                    </a>
                    <div class="small text-white-50">
                        <i class="fas fa-angle-right"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="small text-white-50">Department Giving</div>
                            <div class="h6">Giving by Category</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-building fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white text-decoration-none" href="{{ route('reports.department-giving') }}">
                        View Report
                    </a>
                    <div class="small text-white-50">
                        <i class="fas fa-angle-right"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="small text-white-50">Income vs Expenditure</div>
                            <div class="h6">Financial Performance</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white text-decoration-none" href="{{ route('reports.income-vs-expenditure') }}">
                        View Report
                    </a>
                    <div class="small text-white-50">
                        <i class="fas fa-angle-right"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="small text-white-50">Budget Performance</div>
                            <div class="h6">Budget vs Actual</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-wallet fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white text-decoration-none" href="{{ route('reports.budget-performance') }}">
                        View Report
                    </a>
                    <div class="small text-white-50">
                        <i class="fas fa-angle-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Report Categories
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-primary">Giving Reports</h5>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>Member Giving Analysis</li>
                                <li><i class="fas fa-check text-success me-2"></i>Department/Category Giving</li>
                                <li><i class="fas fa-check text-success me-2"></i>Pledge Tracking</li>
                                <li><i class="fas fa-check text-success me-2"></i>Donation Summary</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-primary">Financial Reports</h5>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>Income vs Expenditure</li>
                                <li><i class="fas fa-check text-success me-2"></i>Budget Performance</li>
                                <li><i class="fas fa-check text-success me-2"></i>Expense Analysis</li>
                                <li><i class="fas fa-check text-success me-2"></i>Financial Trends</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle me-1"></i>
                    Report Features
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary">Export Options</h6>
                        <p class="small text-muted">Export reports to PDF or Excel format for sharing and archiving.</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-primary">Date Filtering</h6>
                        <p class="small text-muted">Filter reports by custom date ranges for specific periods.</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-primary">Real-time Data</h6>
                        <p class="small text-muted">All reports are generated with the latest financial data.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Reports -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-history me-1"></i>
            Quick Access
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-user-chart fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Member Giving</h5>
                            <p class="card-text">Analyze individual member contributions and giving patterns.</p>
                            <a href="{{ route('reports.member-giving') }}" class="btn btn-primary">View Report</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-building fa-3x text-success mb-3"></i>
                            <h5 class="card-title">Department Giving</h5>
                            <p class="card-text">View giving breakdown by departments and categories.</p>
                            <a href="{{ route('reports.department-giving') }}" class="btn btn-success">View Report</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-chart-line fa-3x text-warning mb-3"></i>
                            <h5 class="card-title">Income vs Expenditure</h5>
                            <p class="card-text">Compare income against expenses for financial health.</p>
                            <a href="{{ route('reports.income-vs-expenditure') }}" class="btn btn-warning">View Report</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-wallet fa-3x text-info mb-3"></i>
                            <h5 class="card-title">Budget Performance</h5>
                            <p class="card-text">Track budget utilization and performance metrics.</p>
                            <a href="{{ route('reports.budget-performance') }}" class="btn btn-info">View Report</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function exportReport(format) {
    const reportType = prompt('Enter report type (member-giving, department-giving, income-vs-expenditure, budget-performance):');
    if (!reportType) return;
    
    const startDate = prompt('Enter start date (YYYY-MM-DD):', new Date().getFullYear() + '-01-01');
    if (!startDate) return;
    
    const endDate = prompt('Enter end date (YYYY-MM-DD):', new Date().toISOString().split('T')[0]);
    if (!endDate) return;
    
    const url = `/reports/export/${format}?report_type=${reportType}&start_date=${startDate}&end_date=${endDate}`;
    
    if (format === 'pdf') {
        window.open(url, '_blank');
    } else {
        window.location.href = url;
    }
}
</script>
@endsection

