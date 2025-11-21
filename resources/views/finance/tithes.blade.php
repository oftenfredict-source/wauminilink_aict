@extends('layouts.index')

@section('content')
@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        });
    </script>
@endif

@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        });
    </script>
@endif

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4"><i class="fas fa-coins me-2"></i>Tithes Management</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTitheModal">
            <i class="fas fa-plus me-1"></i>Add Tithe
        </button>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-filter me-1"></i><strong>Filters</strong>
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
        <div class="card-header bg-primary text-white">
            <i class="fas fa-table me-1"></i>
            <strong>Tithes Records</strong>
            <span class="badge bg-white text-primary ms-2 fw-bold">{{ $tithes->total() }} {{ $tithes->total() == 1 ? 'record' : 'records' }}</span>
        </div>
        <div class="card-body">
            @if($tithes->total() > 0 && $tithes->count() == 0)
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Note:</strong> There are {{ $tithes->total() }} total record(s), but none are displayed on this page. 
                    @if($tithes->hasPages())
                        Try navigating to <a href="{{ $tithes->url(1) }}" class="alert-link">page 1</a> or check your filters.
                    @endif
                </div>
            @endif
            @if($tithes->count() > 0)
                <div class="table-responsive" style="min-height: 200px;">
                    <table class="table table-bordered table-striped table-hover">
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
                                                <div class="fw-bold">{{ $tithe->member ? $tithe->member->full_name : 'Unknown Member' }}</div>
                                                <small class="text-muted">{{ $tithe->member ? $tithe->member->member_id : ($tithe->member_id ? 'ID: ' . $tithe->member_id : 'N/A') }}</small>
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
                                        @if($tithe->approval_status == 'approved')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Approved
                                            </span>
                                        @elseif($tithe->approval_status == 'rejected')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times me-1"></i>Rejected
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock me-1"></i>Pending Approval
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $tithe->recorded_by ?? 'System' }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewTitheModal{{ $tithe->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
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
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content stylish-modal">
            <form action="{{ route('finance.tithes.store') }}" method="POST">
                @csrf
                <div class="modal-header stylish-modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="d-flex align-items-center">
                        <div class="modal-icon-wrapper me-3">
                            <i class="fas fa-coins"></i>
                        </div>
                        <div>
                            <h5 class="modal-title text-white mb-0" id="addTitheModalLabel">
                                <strong>Add New Tithe</strong>
                            </h5>
                            <small class="text-white-50">Record a member's tithe payment</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
                        <div class="col-md-6" id="tithe_reference_group">
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
                <div class="modal-footer stylish-modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary stylish-submit-btn">
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
                        <p>{{ $tithe->member ? $tithe->member->full_name : 'Unknown Member' }} ({{ $tithe->member ? $tithe->member->member_id : ($tithe->member_id ? 'ID: ' . $tithe->member_id : 'N/A') }})</p>
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
                            @if($tithe->approval_status == 'approved')
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>Approved
                                </span>
                            @elseif($tithe->approval_status == 'rejected')
                                <span class="badge bg-danger">
                                    <i class="fas fa-times me-1"></i>Rejected
                                </span>
                            @else
                                <span class="badge bg-warning">
                                    <i class="fas fa-clock me-1"></i>Pending Approval
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

        // Toggle reference number visibility based on payment method
        var methodEl = titheModal.querySelector('#payment_method');
        var refGroup = titheModal.querySelector('#tithe_reference_group');
        var refInput = titheModal.querySelector('#reference_number');
        function updateTitheRefVisibility() {
            var method = methodEl ? methodEl.value : '';
            var hide = method === 'cash' || method === '';
            if (refGroup) {
                refGroup.style.display = hide ? 'none' : '';
            }
            if (refInput) {
                refInput.required = !hide;
                if (hide) refInput.value = '';
            }
        }
        if (methodEl) {
            methodEl.addEventListener('change', updateTitheRefVisibility);
        }
        titheModal.addEventListener('shown.bs.modal', updateTitheRefVisibility);
        // Initialize on load
        updateTitheRefVisibility();
    }
});

</script>
@endsection

@section('styles')
<style>
    /* Stylish Modal Styles */
    .stylish-modal {
        border: none;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        overflow: hidden;
    }
    
    .stylish-modal-header {
        padding: 1.5rem;
        border-bottom: none;
    }
    
    .modal-icon-wrapper {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
        backdrop-filter: blur(10px);
    }
    
    .stylish-modal .modal-body {
        padding: 2rem;
        background: #f8f9fa;
    }
    
    .stylish-modal .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    
    .stylish-modal .form-control,
    .stylish-modal .form-select {
        border-radius: 8px;
        border: 1.5px solid #e0e0e0;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }
    
    .stylish-modal .form-control:focus,
    .stylish-modal .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        transform: translateY(-1px);
    }
    
    .stylish-modal .form-control:hover,
    .stylish-modal .form-select:hover {
        border-color: #667eea;
    }
    
    .stylish-modal-footer {
        padding: 1.25rem 2rem;
        border-top: 1px solid #e9ecef;
        background: white;
    }
    
    .stylish-submit-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    
    .stylish-submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
    }
    
    .stylish-modal .btn-light {
        border-radius: 8px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .stylish-modal .btn-light:hover {
        background: #e9ecef;
        transform: translateY(-1px);
    }
    
    .stylish-modal .form-check-input {
        width: 1.25rem;
        height: 1.25rem;
        border-radius: 6px;
        border: 2px solid #667eea;
        cursor: pointer;
    }
    
    .stylish-modal .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }
    
    .stylish-modal textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }
    
    /* Animation for modal */
    .modal.fade .modal-dialog {
        transition: transform 0.3s ease-out;
        transform: translate(0, -50px);
    }
    
    .modal.show .modal-dialog {
        transform: none;
    }
    
    /* Input group styling */
    .stylish-modal .input-group-text {
        background: #f8f9fa;
        border: 1.5px solid #e0e0e0;
        border-right: none;
        border-radius: 8px 0 0 8px;
    }
    
    /* Select2 styling in modal */
    .stylish-modal .select2-container--default .select2-selection--single {
        border: 1.5px solid #e0e0e0;
        border-radius: 8px;
        height: auto;
        padding: 0.5rem;
    }
    
    .stylish-modal .select2-container--default .select2-selection--single:focus {
        border-color: #667eea;
    }
    
    /* Prevent scrolling */
    .stylish-modal .modal-body {
        max-height: calc(100vh - 250px);
        overflow-y: auto;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .stylish-modal .modal-body {
            padding: 1.5rem;
            max-height: calc(100vh - 200px);
        }
        
        .stylish-modal-header {
            padding: 1.25rem;
        }
        
        .modal-icon-wrapper {
            width: 40px;
            height: 40px;
            font-size: 20px;
        }
    }
</style>
@endsection

