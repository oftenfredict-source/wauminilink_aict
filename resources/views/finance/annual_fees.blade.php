@extends('layouts.index')

@section('content')
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
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
            document.addEventListener('DOMContentLoaded', function () {
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
        <!-- Page Title and Quick Actions -->
        <div class="card border-0 shadow-sm mb-3 actions-card">
            <div class="card-header bg-white border-bottom p-2 px-3 d-flex align-items-center justify-content-between actions-header"
                onclick="toggleActions()">
                <div class="d-flex align-items-center gap-2">
                    <h1 class="mb-0 mt-2" style="font-size: 1.5rem;"><i
                            class="fas fa-calendar-check me-2 text-primary"></i>Annual Fees Management</h1>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-chevron-down text-muted d-md-none" id="actionsToggleIcon"></i>
                </div>
            </div>
            <div class="card-body p-3" id="actionsBody">
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#recordAnnualFeeModal">
                        <i class="fas fa-plus me-1"></i>
                        <span class="d-none d-sm-inline">Record Payment</span>
                        <span class="d-sm-none">Record</span>
                    </button>
                    <div class="ms-auto d-flex align-items-center gap-2">
                        <span class="badge bg-light text-dark border p-2 me-2">
                            <i class="fas fa-user-tie me-1 text-primary"></i>
                            Adult: {{ number_format($feeAmountAdult) }} TZS
                        </span>
                        <span class="badge bg-light text-dark border p-2">
                            <i class="fas fa-child me-1 text-info"></i>
                            Child: {{ number_format($feeAmountChild) }} TZS
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters & Search -->
        <form method="GET" action="{{ route('finance.annual_fees') }}" class="card mb-4 border-0 shadow-sm"
            id="filtersForm">
            <div class="card-header bg-primary text-white p-2 px-3 filter-header" onclick="toggleFilters()">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-filter me-1"></i>
                        <span class="fw-semibold">Filters</span>
                        @if(request('member_id') || request('year') || request('status'))
                            <span class="badge bg-white text-primary rounded-pill ms-2">
                                {{ (request('member_id') ? 1 : 0) + (request('year') ? 1 : 0) + (request('status') ? 1 : 0) }}
                            </span>
                        @endif
                    </div>
                    <i class="fas fa-chevron-down text-white d-md-none" id="filterToggleIcon"></i>
                </div>
            </div>

            <div class="card-body p-3" id="filterBody">
                <div class="row g-2 mb-2">
                    <div class="col-12 col-md-4">
                        <label for="member_id" class="form-label small text-muted mb-1">Member</label>
                        <select class="form-select form-select-sm select2-member" id="filter_member_id" name="member_id">
                            <option value="">All Payers</option>
                            @foreach($payers as $payer)
                                <option value="{{ $payer->prefixed_id }}" {{ request('member_id') == $payer->prefixed_id ? 'selected' : '' }}>
                                    {{ $payer->full_name }} ({{ $payer->member_id ?? 'Child' }}) {{ $payer->envelope_number ? ' - Env: ' . $payer->envelope_number : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-md-3">
                        <label for="year" class="form-label small text-muted mb-1">Year</label>
                        <select class="form-select form-select-sm" id="year" name="year">
                            <option value="">All Years</option>
                            @for($y = date('Y') + 1; $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-6 col-md-3">
                        <label for="status" class="form-label small text-muted mb-1">Status</label>
                        <select class="form-select form-select-sm" id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                        <a href="{{ route('finance.annual_fees') }}" class="btn btn-outline-secondary btn-sm w-100">
                            Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>

        <!-- Annual Fees Table -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="px-3 py-3 border-0">Member</th>
                                <th class="py-3 border-0 text-center">Envelope No.</th>
                                <th class="py-3 border-0 text-center">Year</th>
                                <th class="py-3 border-0 text-center">Category</th>
                                <th class="py-3 border-0 text-end">Amount</th>
                                <th class="py-3 border-0 text-center">Date</th>
                                <th class="py-3 border-0 text-center">Status</th>
                                <th class="py-3 border-0 text-center">Approver</th>
                                <th class="py-3 border-0 text-end px-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @forelse($annualFees as $fee)
                                <tr>
                                    <td class="px-3 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="member-avatar-sm bg-light text-primary rounded-circle d-flex align-items-center justify-content-center me-2"
                                                style="width: 32px; height: 32px;">
                                                <i class="fas fa-user small"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $fee->member->full_name ?? $fee->child->full_name ?? 'N/A' }}</div>
                                                <div class="small text-muted">{{ $fee->member->member_id ?? ($fee->child ? 'Child ID: '.$fee->child->id : 'N/A') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-center">
                                        @if(($fee->member->envelope_number ?? $fee->child->envelope_number ?? null))
                                            <span class="badge bg-info-soft text-info rounded-pill px-2" style="font-size: 0.75rem;">
                                                {{ $fee->member->envelope_number ?? $fee->child->envelope_number }}
                                            </span>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-center fw-bold">{{ $fee->year }}</td>
                                    <td class="py-3 text-center">
                                        @if($fee->category === 'Adult')
                                            <span class="badge bg-primary-soft text-primary rounded-pill px-2" style="font-size: 0.75rem;">
                                                <i class="fas fa-user-tie me-1"></i>Adult
                                            </span>
                                        @else
                                            <span class="badge bg-info-soft text-info rounded-pill px-2" style="font-size: 0.75rem;">
                                                <i class="fas fa-child me-1"></i>Child
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-end fw-bold text-success">{{ number_format($fee->amount) }}</td>
                                    <td class="py-3 text-center small">
                                        {{ \Carbon\Carbon::parse($fee->payment_date)->format('M d, Y') }}
                                        @if($fee->payment_method)
                                            <div class="text-muted text-uppercase" style="font-size: 0.75rem;">
                                                {{ $fee->payment_method }}</div>
                                        @endif
                                    </td>
                                    <td class="py-3 text-center">
                                        @if($fee->approval_status === 'approved')
                                            <span class="badge bg-success-soft text-success rounded-pill px-3">Approved</span>
                                        @elseif($fee->approval_status === 'pending')
                                            <span
                                                class="badge bg-warning-soft text-warning rounded-pill px-3 animate-pulse">Pending</span>
                                        @else
                                            <span class="badge bg-danger-soft text-danger rounded-pill px-3">Rejected</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-center small text-muted">
                                        @if($fee->approval_status === 'approved')
                                            {{ $fee->approver->name ?? 'System' }}
                                            <div style="font-size: 0.7rem;">
                                                {{ $fee->approved_at ? $fee->approved_at->format('Y-m-d H:i') : '' }}</div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="py-3 text-end px-3">
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm rounded-circle" type="button"
                                                data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                                <li><a class="dropdown-item" href="#" onclick="viewDetails({{ $fee->id }})"><i
                                                            class="fas fa-info-circle me-2 text-info"></i>Details</a></li>
                                                @if($fee->approval_status === 'pending' && auth()->user()->canApproveFinances())
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li><a class="dropdown-item text-success" href="#"
                                                            onclick="approveFee({{ $fee->id }})"><i
                                                                class="fas fa-check-circle me-2"></i>Approve</a></li>
                                                    <li><a class="dropdown-item text-danger" href="#"
                                                            onclick="rejectFee({{ $fee->id }})"><i
                                                                class="fas fa-times-circle me-2"></i>Reject</a></li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="text-muted mb-2"><i class="fas fa-folder-open fa-3x"></i></div>
                                        <p class="mb-0">No annual fee records found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($annualFees->hasPages())
                <div class="card-footer bg-white border-0 py-3">
                    {{ $annualFees->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Record Annual Fee Modal -->
    <div class="modal fade" id="recordAnnualFeeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 shadow-sm" style="background-color: #940000 !important; color: white !important;">
                    <h5 class="modal-title font-weight-bold text-white"><i class="fas fa-calendar-plus me-2"></i>Record Annual Fee Payment</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('finance.annual_fees.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="category" class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                                <select class="form-select" id="modal_category" name="category" required>
                                    <option value="Adult" selected>Adult</option>
                                    <option value="Child">Child</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label for="member_id" class="form-label fw-bold">Member <span
                                        class="text-danger">*</span></label>
                                <select class="form-select select2-member-modal" id="modal_member_id" name="member_id"
                                    required data-placeholder="Select Payer">
                                    <option value=""></option>
                                    @foreach($payers as $payer)
                                        <option value="{{ $payer->prefixed_id }}" data-age="{{ $payer->age }}">
                                            {{ $payer->full_name }} ({{ $payer->member_id ?? 'Child' }}) {{ $payer->envelope_number ? ' - Env: ' . $payer->envelope_number : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="amount" class="form-label fw-bold">Amount (TZS) <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="fas fa-money-bill text-success"></i></span>
                                    <input type="number" class="form-control border-start-0" id="amount" name="amount"
                                        value="{{ $feeAmountAdult }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="year" class="form-label fw-bold">Year <span class="text-danger">*</span></label>
                                <select class="form-select" id="modal_year" name="year" required>
                                    @for($y = date('Y') + 1; $y >= 2020; $y--)
                                        <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="payment_date" class="form-label fw-bold">Payment Date <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="payment_date" name="payment_date"
                                    value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="payment_method" class="form-label fw-bold">Payment Method <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="payment_method" name="payment_method" required>
                                    <option value="cash">Cash</option>
                                    <option value="mobile_money">Mobile Money (M-Pesa/TigoPesa)</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="check">Check</option>
                                </select>
                            </div>
                            <div class="col-md-6" id="reference_number_container">
                                <label for="reference_number" class="form-label fw-bold">Reference Number</label>
                                <input type="text" class="form-control" id="reference_number" name="reference_number"
                                    placeholder="Transaction ID, Receipt #, etc.">
                            </div>
                            <div class="col-12">
                                <label for="notes" class="form-label fw-bold">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="2"
                                    placeholder="Optional notes..."></textarea>
                            </div>
                        </div>

                        <div class="alert alert-info mt-4 mb-0 border-0 bg-light-info">
                            <div class="d-flex">
                                <i class="fas fa-info-circle mt-1 me-2 text-info"></i>
                                <div class="small">
                                    This payment will be sent to the <strong>Treasurer</strong> for
                                    approval before it's added to the financial records.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn px-4 text-white" style="background-color: #940000 !important;">
                            <i class="fas fa-save me-1"></i>Record Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Styles -->
    <style>
        .bg-primary-soft {
            background-color: rgba(0, 123, 255, 0.1);
        }

        .bg-success-soft {
            background-color: rgba(40, 167, 69, 0.1);
        }

        .bg-warning-soft {
            background-color: rgba(255, 193, 7, 0.1);
        }

        .bg-danger-soft {
            background-color: rgba(220, 53, 69, 0.1);
        }

        .bg-light-info {
            background-color: rgba(13, 202, 240, 0.1);
        }

        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: .5;
            }
        }

        .filter-header,
        .actions-header {
            cursor: pointer;
        }

        .filter-header:hover,
        .actions-header:hover {
            background-color: #f8f9fa !important;
        }

        .member-avatar-sm {
            font-size: 0.8rem;
        }
    </style>

    <!-- Scripts -->
    <script>
        function toggleFilters() {
            const body = document.getElementById('filterBody');
            const icon = document.getElementById('filterToggleIcon');
            if (body.style.display === 'none') {
                body.style.display = 'block';
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            } else {
                body.style.display = 'none';
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        }

        function toggleActions() {
            const body = document.getElementById('actionsBody');
            const icon = document.getElementById('actionsToggleIcon');
            if (body.style.display === 'none') {
                body.style.display = 'block';
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            } else {
                body.style.display = 'none';
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        }

        async function approveFee(id) {
            const result = await Swal.fire({
                title: 'Approve Annual Fee?',
                text: "Are you sure you want to approve this payment?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Approve',
                input: 'textarea',
                inputPlaceholder: 'Add approval notes (optional)...'
            });

            if (result.isConfirmed) {
                submitApprovalAction(id, 'approve', result.value);
            }
        }

        async function rejectFee(id) {
            const result = await Swal.fire({
                title: 'Reject Annual Fee?',
                text: "Please provide a reason for rejection:",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Reject',
                input: 'textarea',
                inputRequired: true,
                inputPlaceholder: 'Rejection reason...'
            });

            if (result.isConfirmed) {
                submitApprovalAction(id, 'reject', result.value);
            }
        }

        function submitApprovalAction(id, action, notes) {
            const url = action === 'approve' ? '{{ route("finance.approval.approve") }}' : '{{ route("finance.approval.reject") }}';
            const data = {
                _token: '{{ csrf_token() }}',
                type: 'annual_fee',
                id: id
            };

            if (action === 'approve') data.approval_notes = notes;
            else data.rejection_reason = notes;

            fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(data)
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Success', data.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Error', data.message || 'Something went wrong', 'error');
                    }
                })
                .catch(err => Swal.fire('Error', 'Failed to process request', 'error'));
        }

        function viewDetails(id) {
            fetch(`/finance/approval/view-details/annual_fee/${id}`)
                .then(res => res.json())
                .then(data => {
                    let html = `<div class="text-start small">`;
                    html += `<p><strong>Payer:</strong> ${data.member_name || data.child_name || 'N/A'}</p>`;
                    html += `<p><strong>Year:</strong> ${data.year || '-'}</p>`;
                    html += `<p><strong>Category:</strong> <span class="badge bg-light text-dark border">${data.category || '-'}</span></p>`;
                    html += `<p><strong>Amount:</strong> ${new Intl.NumberFormat().format(data.amount)} TZS</p>`;
                    html += `<p><strong>Date:</strong> ${data.date}</p>`;
                    html += `<p><strong>Method:</strong> ${data.payment_method || '-'}</p>`;
                    html += `<p><strong>Ref #:</strong> ${data.reference_number || '-'}</p>`;
                    html += `<p><strong>Recorded By:</strong> ${data.recorded_by}</p>`;
                    html += `<p><strong>Created At:</strong> ${data.created_at}</p>`;
                    if (data.notes) html += `<p><strong>Notes:</strong><br>${data.notes}</p>`;
                    if (data.approval_status !== 'pending') {
                        html += `<hr>`;
                        html += `<p><strong>Approver:</strong> ${data.approved_by || '-'}</p>`;
                        html += `<p><strong>Approved At:</strong> ${data.approved_at || '-'}</p>`;
                    }
                    html += `</div>`;

                    Swal.fire({
                        title: 'Payment Details',
                        html: html,
                        confirmButtonText: 'Close'
                    });
                });
        }

        document.addEventListener('DOMContentLoaded', function () {
            $('.select2-member').select2({ placeholder: "All Members", allowClear: true, theme: 'bootstrap-5' });
            
            // Member Select2 in Modal
            const $memberModalSelect = $('.select2-member-modal');
            $memberModalSelect.select2({ 
                dropdownParent: $('#recordAnnualFeeModal'), 
                theme: 'bootstrap-5',
                placeholder: "Select Member",
                allowClear: true,
                width: '100%'
            });

            // Handle payment method change to show/hide reference number
            const paymentMethodSelect = document.getElementById('payment_method');
            const referenceContainer = document.getElementById('reference_number_container');
            const referenceInput = document.getElementById('reference_number');

            function toggleReferenceField() {
                if (paymentMethodSelect.value === 'cash') {
                    referenceContainer.style.display = 'none';
                    referenceInput.value = '';
                } else {
                    referenceContainer.style.display = 'block';
                }
            }

            if (paymentMethodSelect) {
                paymentMethodSelect.addEventListener('change', toggleReferenceField);
                // Run on initial load
                toggleReferenceField();
            }

            // Handle category selection to filter members and update amount
            function handleCategoryChange() {
                const category = document.getElementById('modal_category').value;
                const amountInput = document.getElementById('amount');
                
                // Clear member selection
                $memberModalSelect.val(null).trigger('change');
                
                // Update amount based on category
                if (category === 'Child') {
                    amountInput.value = '{{ $feeAmountChild }}';
                } else {
                    amountInput.value = '{{ $feeAmountAdult }}';
                }

                // Filter Select2 options
                $memberModalSelect.find('option').each(function() {
                    const age = $(this).data('age');
                    if (!$(this).val()) return; // Skip empty option

                    if (category === 'Child') {
                        if (age < 21) {
                            $(this).prop('disabled', false).show();
                        } else {
                            $(this).prop('disabled', true).hide();
                        }
                    } else {
                        if (age >= 21) {
                            $(this).prop('disabled', false).show();
                        } else {
                            $(this).prop('disabled', true).hide();
                        }
                    }
                });

                // Refresh Select2
                $memberModalSelect.select2({ 
                    dropdownParent: $('#recordAnnualFeeModal'),
                    width: '100%',
                    allowClear: true
                });
            }

            document.getElementById('modal_category').addEventListener('change', handleCategoryChange);

            // Run initial filter on page load/modal open
            $('#recordAnnualFeeModal').on('show.bs.modal', function() {
                handleCategoryChange();
            });

            // Ensure Select2 width is correct when modal opens
            $('#recordAnnualFeeModal').on('shown.bs.modal', function() {
                $memberModalSelect.select2({ 
                    dropdownParent: $('#recordAnnualFeeModal'), 
                    theme: 'bootstrap-5',
                    placeholder: "Select Payer",
                    allowClear: true,
                    width: '100%'
                });
            });
        });
    </script>
@endsection