@extends('layouts.index')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4"><i class="fas fa-receipt me-2"></i>Expenses Management</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
            <i class="fas fa-plus me-1"></i>Add Expense
        </button>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-filter me-1"></i>Filters
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('finance.expenses') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label for="expense_category" class="form-label">Category</label>
                        <select class="form-select" id="expense_category" name="expense_category">
                            <option value="">All Categories</option>
                            <option value="utilities" {{ request('expense_category') == 'utilities' ? 'selected' : '' }}>Utilities</option>
                            <option value="maintenance" {{ request('expense_category') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="supplies" {{ request('expense_category') == 'supplies' ? 'selected' : '' }}>Supplies</option>
                            <option value="transport" {{ request('expense_category') == 'transport' ? 'selected' : '' }}>Transport</option>
                            <option value="communication" {{ request('expense_category') == 'communication' ? 'selected' : '' }}>Communication</option>
                            <option value="other" {{ request('expense_category') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="date_from" class="form-label">From Date</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="date_to" class="form-label">To Date</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('finance.expenses') }}" class="btn btn-secondary">Clear</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Expenses Table -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>Expenses List
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Expense Name</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Vendor</th>
                            <th>Budget</th>
                            <th>Status</th>
                            <th>Actions</th>
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
                            <td>{{ $expense->budget->budget_name ?? '-' }}</td>
                            <td>
                                @if($expense->status == 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @elseif($expense->status == 'approved')
                                    <span class="badge bg-primary">Approved</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="viewExpense({{ $expense->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($expense->status == 'pending')
                                        <button type="button" class="btn btn-sm btn-outline-success" onclick="approveExpense({{ $expense->id }})">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif
                                    @if($expense->status == 'approved')
                                        <button type="button" class="btn btn-sm btn-outline-info" onclick="markPaid({{ $expense->id }})">
                                            <i class="fas fa-dollar-sign"></i>
                                        </button>
                                    @endif
                                    <button type="button" class="btn btn-sm btn-outline-warning" onclick="editExpense({{ $expense->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No expenses found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $expenses->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Add Expense Modal -->
<div class="modal fade" id="addExpenseModal" tabindex="-1" aria-labelledby="addExpenseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addExpenseModalLabel">Add New Expense</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('finance.expenses.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="budget_id" class="form-label">Budget</label>
                                <select class="form-select" id="budget_id" name="budget_id">
                                    <option value="">Select Budget (Optional)</option>
                                    @foreach($budgets as $budget)
                                        <option value="{{ $budget->id }}">{{ $budget->budget_name }} ({{ $budget->fiscal_year }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="expense_category" class="form-label">Category *</label>
                                <select class="form-select" id="expense_category" name="expense_category" required>
                                    <option value="">Select Category</option>
                                    <option value="utilities">Utilities</option>
                                    <option value="maintenance">Maintenance</option>
                                    <option value="supplies">Supplies</option>
                                    <option value="transport">Transport</option>
                                    <option value="communication">Communication</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="expense_name" class="form-label">Expense Name *</label>
                                <input type="text" class="form-control" id="expense_name" name="expense_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount *</label>
                                <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="0" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="expense_date" class="form-label">Expense Date *</label>
                                <input type="date" class="form-control" id="expense_date" name="expense_date" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="payment_method" class="form-label">Payment Method *</label>
                                <select class="form-select" id="payment_method" name="payment_method" required>
                                    <option value="">Select Method</option>
                                    <option value="cash">Cash</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="mobile_money">Mobile Money</option>
                                    <option value="check">Check</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="vendor" class="form-label">Vendor</label>
                                <input type="text" class="form-control" id="vendor" name="vendor">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="reference_number" class="form-label">Reference Number</label>
                                <input type="text" class="form-control" id="reference_number" name="reference_number">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="receipt_number" class="form-label">Receipt Number</label>
                                <input type="text" class="form-control" id="receipt_number" name="receipt_number">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Expense</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Approve Expense Modal -->
<div class="modal fade" id="approveExpenseModal" tabindex="-1" aria-labelledby="approveExpenseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveExpenseModalLabel">Approve Expense</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="approveExpenseForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="approved_by" class="form-label">Approved By *</label>
                        <input type="text" class="form-control" id="approved_by" name="approved_by" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Approve Expense</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function viewExpense(id) {
    // Implementation for viewing expense details
    console.log('View expense:', id);
}

function approveExpense(id) {
    document.getElementById('approveExpenseForm').action = `/finance/expenses/${id}/approve`;
    const modal = new bootstrap.Modal(document.getElementById('approveExpenseModal'));
    modal.show();
}

function markPaid(id) {
    if (confirm('Mark this expense as paid?')) {
        fetch(`/finance/expenses/${id}/paid`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred');
        });
    }
}

function editExpense(id) {
    // Implementation for editing expense
    console.log('Edit expense:', id);
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

