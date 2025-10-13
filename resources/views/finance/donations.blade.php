@extends('layouts.index')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4"><i class="fas fa-heart me-2"></i>Donations Management</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDonationModal">
            <i class="fas fa-plus me-1"></i>Add Donation
        </button>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-filter me-1"></i>Filters
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('finance.donations') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label for="donation_type" class="form-label">Donation Type</label>
                        <select class="form-select" id="donation_type" name="donation_type">
                            <option value="">All Types</option>
                            <option value="general" {{ request('donation_type') == 'general' ? 'selected' : '' }}>General</option>
                            <option value="building" {{ request('donation_type') == 'building' ? 'selected' : '' }}>Building Fund</option>
                            <option value="mission" {{ request('donation_type') == 'mission' ? 'selected' : '' }}>Mission</option>
                            <option value="special" {{ request('donation_type') == 'special' ? 'selected' : '' }}>Special Project</option>
                            <option value="other" {{ request('donation_type') == 'other' ? 'selected' : '' }}>Other</option>
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

    <!-- Donations Table -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>Donations List
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Donor</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Purpose</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($donations as $donation)
                        <tr>
                            <td>{{ $donation->donation_date->format('M d, Y') }}</td>
                            <td>
                                @if($donation->is_anonymous)
                                    <span class="text-muted">Anonymous</span>
                                @elseif($donation->member)
                                    {{ $donation->member->full_name }}
                                @else
                                    {{ $donation->donor_name ?? 'Unknown' }}
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($donation->donation_type) }}</span>
                            </td>
                            <td class="text-end">TZS {{ number_format($donation->amount, 0) }}</td>
                            <td>{{ ucfirst($donation->payment_method) }}</td>
                            <td>{{ $donation->purpose ?? '-' }}</td>
                            <td>
                                @if($donation->is_verified)
                                    <span class="badge bg-success">Verified</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="viewDonation({{ $donation->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-success" onclick="editDonation({{ $donation->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No donations found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $donations->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Add Donation Modal -->
<div class="modal fade" id="addDonationModal" tabindex="-1" aria-labelledby="addDonationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDonationModalLabel">Add New Donation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('finance.donations.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="member_id" class="form-label">Member (Optional)</label>
                                <select class="form-select select2-member-modal" id="member_id" name="member_id">
                                    <option value="">Select Member (or leave blank for non-member)</option>
                                    @foreach($members as $member)
                                        <option value="{{ $member->id }}">{{ $member->full_name }} ({{ $member->member_id }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="donation_type" class="form-label">Donation Type *</label>
                                <select class="form-select" id="donation_type" name="donation_type" required>
                                    <option value="">Select Type</option>
                                    <option value="general">General</option>
                                    <option value="building">Building Fund</option>
                                    <option value="mission">Mission</option>
                                    <option value="special">Special Project</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="donor_name" class="form-label">Donor Name (if not a member)</label>
                                <input type="text" class="form-control" id="donor_name" name="donor_name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="donor_email" class="form-label">Donor Email</label>
                                <input type="email" class="form-control" id="donor_email" name="donor_email">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="donor_phone" class="form-label">Donor Phone</label>
                                <input type="text" class="form-control" id="donor_phone" name="donor_phone">
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
                                <label for="donation_date" class="form-label">Donation Date *</label>
                                <input type="date" class="form-control" id="donation_date" name="donation_date" value="{{ date('Y-m-d') }}" required>
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
                                <label for="reference_number" class="form-label">Reference Number</label>
                                <input type="text" class="form-control" id="reference_number" name="reference_number">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="purpose" class="form-label">Purpose</label>
                                <input type="text" class="form-control" id="purpose" name="purpose">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_verified" name="is_verified" value="1">
                                <label class="form-check-label" for="is_verified">
                                    Verified
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_anonymous" name="is_anonymous" value="1">
                                <label class="form-check-label" for="is_anonymous">
                                    Anonymous Donation
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Donation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Initialize Select2 for member dropdowns
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 for modal dropdown
    $('.select2-member-modal').select2({
        placeholder: 'Search for a member...',
        allowClear: true,
        width: '100%',
        dropdownParent: $('#addDonationModal')
    });
});

function viewDonation(id) {
    // Implementation for viewing donation details
    console.log('View donation:', id);
}

function editDonation(id) {
    // Implementation for editing donation
    console.log('Edit donation:', id);
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
