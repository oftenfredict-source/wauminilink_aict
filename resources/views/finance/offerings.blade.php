@extends('layouts.index')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4"><i class="fas fa-gift me-2"></i>Offerings Management</h1>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addOfferingModal">
            <i class="fas fa-plus me-1"></i>Add Offering
        </button>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-filter me-1"></i>Filters
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('finance.offerings') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="offering_type" class="form-label">Offering Type</label>
                        <select class="form-select" id="offering_type" name="offering_type">
                            <option value="">All Types</option>
                            <option value="general" {{ request('offering_type') == 'general' ? 'selected' : '' }}>General</option>
                            <option value="special" {{ request('offering_type') == 'special' ? 'selected' : '' }}>Special</option>
                            <option value="thanksgiving" {{ request('offering_type') == 'thanksgiving' ? 'selected' : '' }}>Thanksgiving</option>
                            <option value="building_fund" {{ request('offering_type') == 'building_fund' ? 'selected' : '' }}>Building Fund</option>
                            <option value="missions" {{ request('offering_type') == 'missions' ? 'selected' : '' }}>Missions</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="date_from" class="form-label">From Date</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="date_to" class="form-label">To Date</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-success me-2">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                        <a href="{{ route('finance.offerings') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Offerings Table -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Offerings Records
            <span class="badge bg-success ms-2">{{ $offerings->total() }} records</span>
        </div>
        <div class="card-body">
            @if($offerings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Member/Donor</th>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($offerings as $offering)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                <i class="fas fa-gift text-white small"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $offering->member->full_name ?? 'Anonymous' }}</div>
                                                @if($offering->member)
                                                    <small class="text-muted">{{ $offering->member->member_id }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">TZS {{ number_format($offering->amount, 0) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $offering->offering_type == 'general' ? 'primary' : ($offering->offering_type == 'special' ? 'warning' : 'info') }}">
                                            {{ ucfirst(str_replace('_', ' ', $offering->offering_type)) }}
                                        </span>
                                    </td>
                                    <td>{{ $offering->offering_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $offering->payment_method == 'cash' ? 'success' : ($offering->payment_method == 'bank_transfer' ? 'info' : 'warning') }}">
                                            {{ ucfirst(str_replace('_', ' ', $offering->payment_method)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($offering->is_verified)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Verified
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock me-1"></i>Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#viewOfferingModal{{ $offering->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if(!$offering->is_verified)
                                                <button type="button" class="btn btn-outline-primary" onclick="verifyOffering({{ $offering->id }})">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $offerings->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-gift fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No offerings found</h5>
                    <p class="text-muted">Start by adding a new offering record.</p>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addOfferingModal">
                        <i class="fas fa-plus me-1"></i>Add First Offering
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Offering Modal -->
<div class="modal fade" id="addOfferingModal" tabindex="-1" aria-labelledby="addOfferingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('finance.offerings.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addOfferingModalLabel">Add New Offering</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="member_id" class="form-label">Member (Optional)</label>
                            <select class="form-select select2-member-modal @error('member_id') is-invalid @enderror" id="member_id" name="member_id">
                                <option value="">Anonymous Offering</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                        {{ $member->full_name }} ({{ $member->member_id }})
                                    </option>
                                @endforeach
                            </select>
                            @error('member_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="amount" class="form-label">Amount (TZS) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" 
                                   value="{{ old('amount') }}" min="0" step="0.01" required>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="offering_type" class="form-label">Offering Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('offering_type') is-invalid @enderror" id="offering_type" name="offering_type" required>
                                <option value="">Select Type</option>
                                <option value="general" {{ old('offering_type') == 'general' ? 'selected' : '' }}>General</option>
                                <option value="special" {{ old('offering_type') == 'special' ? 'selected' : '' }}>Special</option>
                                <option value="thanksgiving" {{ old('offering_type') == 'thanksgiving' ? 'selected' : '' }}>Thanksgiving</option>
                                <option value="building_fund" {{ old('offering_type') == 'building_fund' ? 'selected' : '' }}>Building Fund</option>
                                <option value="missions" {{ old('offering_type') == 'missions' ? 'selected' : '' }}>Missions</option>
                            </select>
                            @error('offering_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="offering_date" class="form-label">Offering Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('offering_date') is-invalid @enderror" id="offering_date" name="offering_date" 
                                   value="{{ old('offering_date', date('Y-m-d')) }}" required>
                            @error('offering_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                            <select class="form-select @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method" required>
                                <option value="">Select Method</option>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>Check</option>
                                <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="mobile_money" {{ old('payment_method') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="reference_number" class="form-label">Reference Number</label>
                            <input type="text" class="form-control @error('reference_number') is-invalid @enderror" id="reference_number" name="reference_number" 
                                   value="{{ old('reference_number') }}" placeholder="e.g., Check #123, Transaction ID">
                            @error('reference_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="service_type" class="form-label">Service Type</label>
                            <select class="form-select @error('service_type') is-invalid @enderror" id="service_type" name="service_type">
                                <option value="">Select Service</option>
                                <option value="sunday_service" {{ old('service_type') == 'sunday_service' ? 'selected' : '' }}>Sunday Service</option>
                                <option value="special_event" {{ old('service_type') == 'special_event' ? 'selected' : '' }}>Special Event</option>
                                <option value="prayer_meeting" {{ old('service_type') == 'prayer_meeting' ? 'selected' : '' }}>Prayer Meeting</option>
                                <option value="other" {{ old('service_type') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('service_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="is_verified" name="is_verified" value="1" {{ old('is_verified') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_verified">
                                    Mark as verified
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3" 
                                      placeholder="Additional notes about this offering...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i>Save Offering
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Offering Modals -->
@foreach($offerings as $offering)
<div class="modal fade" id="viewOfferingModal{{ $offering->id }}" tabindex="-1" aria-labelledby="viewOfferingModalLabel{{ $offering->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewOfferingModalLabel{{ $offering->id }}">Offering Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Member/Donor</label>
                        <p>{{ $offering->member->full_name ?? 'Anonymous' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Amount</label>
                        <p class="h5 text-success">TZS {{ number_format($offering->amount, 0) }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Offering Type</label>
                        <p>
                            <span class="badge bg-{{ $offering->offering_type == 'general' ? 'primary' : ($offering->offering_type == 'special' ? 'warning' : 'info') }}">
                                {{ ucfirst(str_replace('_', ' ', $offering->offering_type)) }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Offering Date</label>
                        <p>{{ $offering->offering_date->format('F d, Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Payment Method</label>
                        <p>
                            <span class="badge bg-{{ $offering->payment_method == 'cash' ? 'success' : ($offering->payment_method == 'bank_transfer' ? 'info' : 'warning') }}">
                                {{ ucfirst(str_replace('_', ' ', $offering->payment_method)) }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Status</label>
                        <p>
                            @if($offering->is_verified)
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>Verified
                                </span>
                            @else
                                <span class="badge bg-warning">
                                    <i class="fas fa-clock me-1"></i>Pending Verification
                                </span>
                            @endif
                        </p>
                    </div>
                    @if($offering->reference_number)
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Reference Number</label>
                            <p>{{ $offering->reference_number }}</p>
                        </div>
                    @endif
                    @if($offering->service_type)
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Service Type</label>
                            <p>{{ ucfirst(str_replace('_', ' ', $offering->service_type)) }}</p>
                        </div>
                    @endif
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Recorded By</label>
                        <p>{{ $offering->recorded_by ?? 'System' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Recorded On</label>
                        <p>{{ $offering->created_at->format('F d, Y g:i A') }}</p>
                    </div>
                    @if($offering->notes)
                        <div class="col-12">
                            <label class="form-label fw-bold">Notes</label>
                            <p>{{ $offering->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                @if(!$offering->is_verified)
                    <button type="button" class="btn btn-success" onclick="verifyOffering({{ $offering->id }})">
                        <i class="fas fa-check me-1"></i>Verify
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach

<script>
// Initialize Select2 for member dropdowns
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 for modal dropdown
    $('.select2-member-modal').select2({
        placeholder: 'Search for a member...',
        allowClear: true,
        width: '100%',
        dropdownParent: $('#addOfferingModal')
    });
});

function verifyOffering(offeringId) {
    if (confirm('Are you sure you want to verify this offering?')) {
        // This would typically be an AJAX call to verify the offering
        // For now, we'll just show a success message
        alert('Offering verification functionality will be implemented with AJAX');
    }
}
</script>
@endsection

