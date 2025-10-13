@extends('layouts.index')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4"><i class="fas fa-coins me-2"></i>Tithes Management</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTitheModal">
            <i class="fas fa-plus me-1"></i>Add Tithe
        </button>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-filter me-1"></i>Filters
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('finance.tithes') }}">
                <div class="row g-3">
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
                    <div class="col-md-2">
                        <label for="date_from" class="form-label">From Date</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="date_to" class="form-label">To Date</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="payment_method" class="form-label">Payment Method</label>
                        <select class="form-select" id="payment_method" name="payment_method">
                            <option value="">All Methods</option>
                            <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="check" {{ request('payment_method') == 'check' ? 'selected' : '' }}>Check</option>
                            <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="mobile_money" {{ request('payment_method') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                        <a href="{{ route('finance.tithes') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tithes Table -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tithes Records
            <span class="badge bg-primary ms-2">{{ $tithes->total() }} records</span>
        </div>
        <div class="card-body">
            @if($tithes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Payment Method</th>
                                <th>Reference</th>
                                <th>Status</th>
                                <th>Recorded By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tithes as $tithe)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                <i class="fas fa-user text-white small"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $tithe->member->full_name ?? 'Unknown' }}</div>
                                                <small class="text-muted">{{ $tithe->member->member_id ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-primary">TZS {{ number_format($tithe->amount, 0) }}</span>
                                    </td>
                                    <td>{{ $tithe->tithe_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $tithe->payment_method == 'cash' ? 'success' : ($tithe->payment_method == 'bank_transfer' ? 'info' : 'warning') }}">
                                            {{ ucfirst(str_replace('_', ' ', $tithe->payment_method)) }}
                                        </span>
                                    </td>
                                    <td>{{ $tithe->reference_number ?? 'N/A' }}</td>
                                    <td>
                                        @if($tithe->is_verified)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Verified
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock me-1"></i>Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $tithe->recorded_by ?? 'System' }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewTitheModal{{ $tithe->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if(!$tithe->is_verified)
                                                <button type="button" class="btn btn-outline-success" onclick="verifyTithe({{ $tithe->id }})">
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
                    {{ $tithes->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-coins fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No tithes found</h5>
                    <p class="text-muted">Start by adding a new tithe record.</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTitheModal">
                        <i class="fas fa-plus me-1"></i>Add First Tithe
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Tithe Modal -->
<div class="modal fade" id="addTitheModal" tabindex="-1" aria-labelledby="addTitheModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('finance.tithes.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addTitheModalLabel">Add New Tithe</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="tithe_member_id" class="form-label">Member <span class="text-danger">*</span></label>
                            <select class="form-select select2-member-modal @error('member_id') is-invalid @enderror" id="tithe_member_id" name="member_id" required>
                                <option value="">Select Member</option>
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
                            <label for="tithe_date" class="form-label">Tithe Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tithe_date') is-invalid @enderror" id="tithe_date" name="tithe_date" 
                                   value="{{ old('tithe_date', date('Y-m-d')) }}" required>
                            @error('tithe_date')
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
                                      placeholder="Additional notes about this tithe...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Save Tithe
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Tithe Modals -->
@foreach($tithes as $tithe)
<div class="modal fade" id="viewTitheModal{{ $tithe->id }}" tabindex="-1" aria-labelledby="viewTitheModalLabel{{ $tithe->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewTitheModalLabel{{ $tithe->id }}">Tithe Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Member</label>
                        <p>{{ $tithe->member->full_name ?? 'Unknown' }} ({{ $tithe->member->member_id ?? 'N/A' }})</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Amount</label>
                        <p class="h5 text-primary">TZS {{ number_format($tithe->amount, 0) }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tithe Date</label>
                        <p>{{ $tithe->tithe_date->format('F d, Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Payment Method</label>
                        <p>
                            <span class="badge bg-{{ $tithe->payment_method == 'cash' ? 'success' : ($tithe->payment_method == 'bank_transfer' ? 'info' : 'warning') }}">
                                {{ ucfirst(str_replace('_', ' ', $tithe->payment_method)) }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Reference Number</label>
                        <p>{{ $tithe->reference_number ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Status</label>
                        <p>
                            @if($tithe->is_verified)
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
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Recorded By</label>
                        <p>{{ $tithe->recorded_by ?? 'System' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Recorded On</label>
                        <p>{{ $tithe->created_at->format('F d, Y g:i A') }}</p>
                    </div>
                    @if($tithe->notes)
                        <div class="col-12">
                            <label class="form-label fw-bold">Notes</label>
                            <p>{{ $tithe->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                @if(!$tithe->is_verified)
                    <button type="button" class="btn btn-success" onclick="verifyTithe({{ $tithe->id }})">
                        <i class="fas fa-check me-1"></i>Verify
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach

<script>
// Initialize Select2 using DOMContentLoaded so jQuery isn't required at registration time
document.addEventListener('DOMContentLoaded', function() {
    function initSelect2Now() {
        if (window.jQuery && jQuery.fn && typeof jQuery.fn.select2 === 'function') {
            // Filter dropdown at top
            var filterEls = document.querySelectorAll('.select2-member');
            if (filterEls.length) {
                $('.select2-member').select2({
                    placeholder: 'Search for a member...',
                    allowClear: true,
                    width: '100%',
                    minimumResultsForSearch: 0,
                    language: { noResults: function() { return 'No members found'; } }
                });
            }
            // Modal dropdown in Add Tithe
            var modalEls = document.querySelectorAll('.select2-member-modal');
            if (modalEls.length) {
                $('.select2-member-modal').select2({
                    placeholder: 'Search for a member...',
                    allowClear: true,
                    width: '100%',
                    minimumResultsForSearch: 0,
                    dropdownParent: $('#addTitheModal'),
                    language: { noResults: function() { return 'No members found'; } }
                });
            }
            return true;
        }
        return false;
    }

    // Retry until Select2 is available (max ~5 seconds)
    var attempts = 0;
    (function waitForSelect2() {
        if (initSelect2Now()) return;
        if (attempts++ < 20) {
            setTimeout(waitForSelect2, 250);
        }
    })();

    if (window.jQuery && jQuery.fn && typeof jQuery.fn.select2 === 'function') {
        // Filter dropdown at top
        var filterEls = document.querySelectorAll('.select2-member');
        if (filterEls.length) {
            $('.select2-member').select2({
                placeholder: 'Search for a member...',
                allowClear: true,
                width: '100%',
                minimumResultsForSearch: 0,
                language: { noResults: function() { return 'No members found'; } }
            });
        }
        // Modal dropdown in Add Tithe
        var modalEls = document.querySelectorAll('.select2-member-modal');
        if (modalEls.length) {
            $('.select2-member-modal').select2({
                placeholder: 'Search for a member...',
                allowClear: true,
                width: '100%',
                minimumResultsForSearch: 0,
                dropdownParent: $('#addTitheModal'),
                language: { noResults: function() { return 'No members found'; } }
            });
        }
    }
    // Bind Bootstrap modal event using native listener
    var titheModal = document.getElementById('addTitheModal');
    if (titheModal) {
        titheModal.addEventListener('shown.bs.modal', function() {
            // Always try init on modal show (handles late asset load)
            initSelect2Now();
        });
    }
});

function verifyTithe(titheId) {
    if (confirm('Are you sure you want to verify this tithe?')) {
        // This would typically be an AJAX call to verify the tithe
        // For now, we'll just show a success message
        alert('Tithe verification functionality will be implemented with AJAX');
    }
}
</script>
@endsection

