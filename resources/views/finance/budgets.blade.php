@extends('layouts.index')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4"><i class="fas fa-wallet me-2"></i>Budgets Management</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBudgetModal">
            <i class="fas fa-plus me-1"></i>Add Budget
        </button>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-filter me-1"></i>Filters
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('finance.budgets') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label for="fiscal_year" class="form-label">Fiscal Year</label>
                        <select class="form-select" id="fiscal_year" name="fiscal_year">
                            <option value="">All Years</option>
                            @for($year = date('Y') - 2; $year <= date('Y') + 2; $year++)
                                <option value="{{ $year }}" {{ request('fiscal_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="budget_type" class="form-label">Budget Type</label>
                        <select class="form-select" id="budget_type" name="budget_type">
                            <option value="">All Types</option>
                            <option value="operational" {{ request('budget_type') == 'operational' ? 'selected' : '' }}>Operational</option>
                            <option value="capital" {{ request('budget_type') == 'capital' ? 'selected' : '' }}>Capital</option>
                            <option value="program" {{ request('budget_type') == 'program' ? 'selected' : '' }}>Program</option>
                            <option value="special" {{ request('budget_type') == 'special' ? 'selected' : '' }}>Special</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Budgets Table -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>Budgets List
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Budget Name</th>
                            <th>Type</th>
                            <th>Fiscal Year</th>
                            <th>Total Budget</th>
                            <th>Spent Amount</th>
                            <th>Remaining</th>
                            <th>Utilization</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($budgets as $budget)
                        <tr>
                            <td>{{ $budget->budget_name }}</td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($budget->budget_type) }}</span>
                            </td>
                            <td>{{ $budget->fiscal_year }}</td>
                            <td class="text-end">TZS {{ number_format($budget->total_budget, 0) }}</td>
                            <td class="text-end">TZS {{ number_format($budget->spent_amount, 0) }}</td>
                            <td class="text-end">TZS {{ number_format($budget->remaining_amount, 0) }}</td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar {{ $budget->is_over_budget ? 'bg-danger' : ($budget->is_near_limit ? 'bg-warning' : 'bg-success') }}" 
                                         style="width: {{ $budget->utilization_percentage }}%">
                                        {{ $budget->utilization_percentage }}%
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($budget->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($budget->status == 'completed')
                                    <span class="badge bg-primary">Completed</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="viewBudget({{ $budget->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-warning" onclick="editBudget({{ $budget->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">No budgets found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $budgets->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Add Budget Modal -->
<div class="modal fade" id="addBudgetModal" tabindex="-1" aria-labelledby="addBudgetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBudgetModalLabel">Add New Budget</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('finance.budgets.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="budget_name" class="form-label">Budget Name *</label>
                                <input type="text" class="form-control" id="budget_name" name="budget_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="budget_type" class="form-label">Budget Type *</label>
                                <select class="form-select" id="budget_type" name="budget_type" required>
                                    <option value="">Select Type</option>
                                    <option value="operational">Operational</option>
                                    <option value="capital">Capital</option>
                                    <option value="program">Program</option>
                                    <option value="special">Special</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fiscal_year" class="form-label">Fiscal Year *</label>
                                <select class="form-select" id="fiscal_year" name="fiscal_year" required>
                                    <option value="">Select Year</option>
                                    @for($year = date('Y') - 1; $year <= date('Y') + 2; $year++)
                                        <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="total_budget" class="form-label">Total Budget *</label>
                                <input type="number" class="form-control" id="total_budget" name="total_budget" step="0.01" min="0" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start Date *</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">End Date *</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Budget</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function viewBudget(id) {
    // Implementation for viewing budget details
    console.log('View budget:', id);
}

function editBudget(id) {
    // Implementation for editing budget
    console.log('Edit budget:', id);
}

// Auto-hide alerts after 5 seconds
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);
</script>
@endsection

