@extends('layouts.index')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4"><i class="fas fa-handshake me-2"></i>Pledges Management</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPledgeModal">
            <i class="fas fa-plus me-1"></i>Add Pledge
        </button>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-filter me-1"></i>Filters
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('finance.pledges') }}">
                <div class="row">
                        <div class="col-md-3">
                            <label for="member_id" class="form-label">Member</label>
                            <select class="form-select select2-member" id="member_id" name="member_id">
                                <option value="">All Members</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}" {{ request('member_id') == $member->id ? 'selected' : '' }}>
                                        {{ $member->full_name }} ({{ $member->member_id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    <div class="col-md-3">
                        <label for="pledge_type" class="form-label">Pledge Type</label>
                        <select class="form-select" id="pledge_type" name="pledge_type">
                            <option value="">All Types</option>
                            <option value="building" {{ request('pledge_type') == 'building' ? 'selected' : '' }}>Building Fund</option>
                            <option value="mission" {{ request('pledge_type') == 'mission' ? 'selected' : '' }}>Mission</option>
                            <option value="special" {{ request('pledge_type') == 'special' ? 'selected' : '' }}>Special Project</option>
                            <option value="general" {{ request('pledge_type') == 'general' ? 'selected' : '' }}>General</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
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

    <!-- Pledges Table -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>Pledges List
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Member</th>
                            <th>Pledge Type</th>
                            <th>Pledge Amount</th>
                            <th>Amount Paid</th>
                            <th>Progress</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pledges as $pledge)
                        <tr>
                            <td>{{ $pledge->member->full_name ?? 'Unknown' }}</td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($pledge->pledge_type) }}</span>
                            </td>
                            <td class="text-end">TZS {{ number_format($pledge->pledge_amount, 0) }}</td>
                            <td class="text-end">TZS {{ number_format($pledge->amount_paid, 0) }}</td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar {{ $pledge->progress_percentage >= 100 ? 'bg-success' : ($pledge->progress_percentage >= 75 ? 'bg-info' : 'bg-warning') }}" 
                                         style="width: {{ $pledge->progress_percentage }}%">
                                        {{ $pledge->progress_percentage }}%
                                    </div>
                                </div>
                            </td>
                            <td>{{ $pledge->due_date ? $pledge->due_date->format('M d, Y') : '-' }}</td>
                            <td>
                                @if($pledge->status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($pledge->status == 'overdue')
                                    <span class="badge bg-danger">Overdue</span>
                                @else
                                    <span class="badge bg-primary">Active</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="viewPledge({{ $pledge->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-success" onclick="addPayment({{ $pledge->id }})">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-warning" onclick="editPledge({{ $pledge->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No pledges found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $pledges->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Add Pledge Modal -->
<div class="modal fade" id="addPledgeModal" tabindex="-1" aria-labelledby="addPledgeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPledgeModalLabel">Add New Pledge</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('finance.pledges.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="member_id" class="form-label">Member *</label>
                                <select class="form-select select2-member-modal" id="member_id" name="member_id" required>
                                    <option value="">Select Member</option>
                                    @foreach($members as $member)
                                        <option value="{{ $member->id }}">{{ $member->full_name }} ({{ $member->member_id }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="pledge_type" class="form-label">Pledge Type *</label>
                                <select class="form-select" id="pledge_type" name="pledge_type" required>
                                    <option value="">Select Type</option>
                                    <option value="building">Building Fund</option>
                                    <option value="mission">Mission</option>
                                    <option value="special">Special Project</option>
                                    <option value="general">General</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="pledge_amount" class="form-label">Pledge Amount *</label>
                                <input type="number" class="form-control" id="pledge_amount" name="pledge_amount" step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="payment_frequency" class="form-label">Payment Frequency *</label>
                                <select class="form-select" id="payment_frequency" name="payment_frequency" required>
                                    <option value="">Select Frequency</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="quarterly">Quarterly</option>
                                    <option value="annually">Annually</option>
                                    <option value="one_time">One Time</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="pledge_date" class="form-label">Pledge Date *</label>
                                <input type="date" class="form-control" id="pledge_date" name="pledge_date" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="due_date" class="form-label">Due Date</label>
                                <input type="date" class="form-control" id="due_date" name="due_date">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="purpose" class="form-label">Purpose</label>
                        <input type="text" class="form-control" id="purpose" name="purpose">
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Pledge</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Payment Modal -->
<div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPaymentModalLabel">Add Payment to Pledge</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addPaymentForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="payment_amount" class="form-label">Payment Amount *</label>
                        <input type="number" class="form-control" id="payment_amount" name="payment_amount" step="0.01" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="payment_date" class="form-label">Payment Date *</label>
                        <input type="date" class="form-control" id="payment_date" name="payment_date" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Add Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Initialize Select2 for member dropdowns
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 for filter dropdown
    $('.select2-member').select2({
        placeholder: 'Search for a member...',
        allowClear: true,
        width: '100%'
    });
    
    // Initialize Select2 for modal dropdown
    $('.select2-member-modal').select2({
        placeholder: 'Search for a member...',
        allowClear: true,
        width: '100%',
        dropdownParent: $('#addPledgeModal')
    });
});

function viewPledge(id) {
    // Implementation for viewing pledge details
    console.log('View pledge:', id);
}

function addPayment(id) {
    document.getElementById('addPaymentForm').action = `/finance/pledges/${id}/payment`;
    const modal = new bootstrap.Modal(document.getElementById('addPaymentModal'));
    modal.show();
}

function editPledge(id) {
    // Implementation for editing pledge
    console.log('Edit pledge:', id);
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
