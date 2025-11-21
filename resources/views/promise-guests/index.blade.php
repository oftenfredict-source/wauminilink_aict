@extends('layouts.index')

@section('content')
<style>
    .table.interactive-table tbody tr:hover { background-color: #f8f9ff; }
    .table.interactive-table tbody tr td:first-child { border-left: 4px solid #5b2a86; }
    
    .status-badge {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 500;
    }
    .status-pending { background-color: #ffc107; color: #000; }
    .status-notified { background-color: #17a2b8; color: #fff; }
    .status-attended { background-color: #28a745; color: #fff; }
    .status-cancelled { background-color: #dc3545; color: #fff; }
</style>

<div class="container-fluid px-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between mt-4 mb-3 gap-2">
        <h2 class="mb-0">Promise Guests</h2>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPromiseGuestModal">
                <i class="fas fa-plus me-2"></i>Add Promise Guest
            </button>
            <a href="{{ route('promise-guests.create') }}" class="btn btn-outline-primary">
                <i class="fas fa-external-link-alt me-2"></i>Full Form
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total</h6>
                            <h3 class="mb-0">{{ $stats['total'] }}</h3>
                        </div>
                        <i class="fas fa-users fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-dark-50 mb-1">Pending</h6>
                            <h3 class="mb-0">{{ $stats['pending'] }}</h3>
                        </div>
                        <i class="fas fa-clock fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Notified</h6>
                            <h3 class="mb-0">{{ $stats['notified'] }}</h3>
                        </div>
                        <i class="fas fa-bell fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Attended</h6>
                            <h3 class="mb-0">{{ $stats['attended'] }}</h3>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('promise-guests.index') }}" class="card mb-3">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Name or phone number">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="notified" {{ request('status') == 'notified' ? 'selected' : '' }}>Notified</option>
                        <option value="attended" {{ request('status') == 'attended' ? 'selected' : '' }}>Attended</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Service Date</label>
                    <input type="date" name="service_date" value="{{ request('service_date') }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label">From</label>
                    <input type="date" name="from" value="{{ request('from') }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label">To</label>
                    <input type="date" name="to" value="{{ request('to') }}" class="form-control">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter"></i></button>
                </div>
            </div>
        </div>
    </form>

    <!-- Error/Success Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{!! htmlspecialchars_decode(session('success'), ENT_QUOTES) !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any() || session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            @if($errors->any())
                @foreach($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            @elseif(session('error'))
                {{ session('error') }}
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Promise Guests Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Service Date</th>
                            <th>Status</th>
                            <th>Notified At</th>
                            <th>Created By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($promiseGuests as $guest)
                            <tr>
                                <td>
                                    <strong>{{ $guest->name }}</strong>
                                    @if($guest->email)
                                        <br><small class="text-muted">{{ $guest->email }}</small>
                                    @endif
                                </td>
                                <td>{{ $guest->phone_number }}</td>
                                <td>
                                    {{ $guest->promised_service_date->format('d/m/Y') }}
                                    @if($guest->service)
                                        <br><small class="text-muted">
                                            @if($guest->service->theme)
                                                {{ $guest->service->theme }}
                                            @endif
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <span class="status-badge status-{{ $guest->status }}">
                                        {{ ucfirst($guest->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($guest->notified_at)
                                        {{ $guest->notified_at->format('d/m/Y H:i') }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($guest->creator)
                                        {{ $guest->creator->name }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        @if($guest->status == 'pending')
                                            <form action="{{ route('promise-guests.send-notification', $guest) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-info btn-sm" title="Send Notification">
                                                    <i class="fas fa-bell"></i>
                                                </button>
                                            </form>
                                        @endif
                                        @if($guest->status != 'attended')
                                            <form action="{{ route('promise-guests.mark-attended', $guest) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm" title="Mark as Attended">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('promise-guests.edit', $guest) }}" class="btn btn-primary btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('promise-guests.destroy', $guest) }}" method="POST" class="d-inline delete-promise-guest-form" data-guest-name="{{ $guest->name }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No promise guests found.</p>
                                    <a href="{{ route('promise-guests.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Add Promise Guest
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($promiseGuests->hasPages())
            <div class="card-footer">
                {{ $promiseGuests->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Add Promise Guest Modal -->
<div class="modal fade" id="addPromiseGuestModal" tabindex="-1" aria-labelledby="addPromiseGuestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPromiseGuestModalLabel">
                    <i class="fas fa-user-plus me-2"></i>Add Promise Guest
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('promise-guests.store') }}" method="POST" id="addPromiseGuestForm">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="modal_name" class="form-label">Guest Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="modal_name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="modal_phone_number" class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">+255</span>
                                <input type="text" class="form-control @error('phone_number') is-invalid @enderror" 
                                       id="modal_phone_number" name="phone_number" 
                                       placeholder="712345678" 
                                       value="{{ old('phone_number') }}" 
                                       pattern="[0-9]{9,15}" 
                                       maxlength="15"
                                       required>
                            </div>
                            <small class="text-muted">Enter phone number without +255 (e.g., 712345678)</small>
                            @error('phone_number')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="modal_email" class="form-label">Email (Optional)</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="modal_email" name="email" value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="modal_promised_service_date" class="form-label">Promised Service Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('promised_service_date') is-invalid @enderror" 
                                   id="modal_promised_service_date" name="promised_service_date" 
                                   value="{{ old('promised_service_date') }}" 
                                   min="{{ date('Y-m-d') }}" required>
                            @error('promised_service_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="modal_service_id" class="form-label">Sunday Service (Optional)</label>
                            <select class="form-select @error('service_id') is-invalid @enderror" 
                                    id="modal_service_id" name="service_id">
                                <option value="">Select a service (or leave blank)</option>
                                @foreach($upcomingServices as $service)
                                    <option value="{{ $service->id }}" 
                                            data-date="{{ $service->service_date->format('Y-m-d') }}"
                                            {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                        {{ $service->service_date->format('d/m/Y') }}
                                        @if($service->theme)
                                            - {{ $service->theme }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">If selected, service date will be automatically set</small>
                            @error('service_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="modal_notes" class="form-label">Notes (Optional)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="modal_notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Promise Guest
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Auto-format phone number with +255 prefix
    document.addEventListener('DOMContentLoaded', function() {
        const phoneInput = document.getElementById('modal_phone_number');
        const phoneForm = document.getElementById('addPromiseGuestForm');
        
        // Format phone number on input (only allow digits)
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                // Remove any non-digit characters
                this.value = this.value.replace(/\D/g, '');
            });

            // Add +255 prefix before form submission
            if (phoneForm) {
                phoneForm.addEventListener('submit', function(e) {
                    const phoneValue = phoneInput.value.replace(/\s+/g, '');
                    if (phoneValue && /^[0-9]{9,15}$/.test(phoneValue)) {
                        phoneInput.value = '+255' + phoneValue;
                    } else if (phoneValue) {
                        e.preventDefault();
                        alert('Please enter a valid phone number (9-15 digits without +255)');
                        return false;
                    }
                });
            }
        }

        // Auto-update service date when a service is selected in modal
        const modalServiceSelect = document.getElementById('modal_service_id');
        const modalDateInput = document.getElementById('modal_promised_service_date');
        
        if (modalServiceSelect && modalDateInput) {
            modalServiceSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.value && selectedOption.dataset.date) {
                    modalDateInput.value = selectedOption.dataset.date;
                }
            });

            // Auto-update service selection when date changes
            modalDateInput.addEventListener('change', function() {
                const selectedDate = this.value;
                
                // Try to find matching service
                for (let option of modalServiceSelect.options) {
                    if (option.dataset.date === selectedDate) {
                        modalServiceSelect.value = option.value;
                        break;
                    } else {
                        modalServiceSelect.value = '';
                    }
                }
            });
        }

        // Reset modal form when closed
        const modal = document.getElementById('addPromiseGuestModal');
        if (modal) {
            modal.addEventListener('hidden.bs.modal', function() {
                document.getElementById('addPromiseGuestForm').reset();
                // Clear any validation errors
                const invalidInputs = modal.querySelectorAll('.is-invalid');
                invalidInputs.forEach(input => input.classList.remove('is-invalid'));
            });
        }
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // SweetAlert for success messages
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: {!! json_encode(session('success')) !!},
            timer: 3000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    @endif

    // SweetAlert for error messages
    @if($errors->any())
        @foreach($errors->all() as $error)
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: {!! json_encode($error) !!},
                confirmButtonColor: '#dc3545'
            });
        @endforeach
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: {!! json_encode(session('error')) !!},
            confirmButtonColor: '#dc3545'
        });
    @endif

    // Handle delete confirmation with SweetAlert
    document.querySelectorAll('.delete-promise-guest-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const guestName = this.getAttribute('data-guest-name') || 'this promise guest';
            
            Swal.fire({
                title: 'Delete Promise Guest?',
                html: `Are you sure you want to delete <strong>${guestName}</strong>?<br><br>This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Submit the form
                    form.submit();
                }
            });
        });
    });

    // Handle add promise guest form submission with SweetAlert
    const addPromiseGuestForm = document.getElementById('addPromiseGuestForm');
    if (addPromiseGuestForm) {
        addPromiseGuestForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const phoneInput = document.getElementById('modal_phone_number');
            const phoneValue = phoneInput.value.replace(/\s+/g, '').replace(/^\+255/, ''); // Remove +255 if already present
            
            // Validate phone number format
            if (!phoneValue || phoneValue.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Phone Number Required',
                    text: 'Please enter a phone number',
                    confirmButtonColor: '#dc3545'
                });
                phoneInput.focus();
                return false;
            }
            
            if (!/^[0-9]{9,15}$/.test(phoneValue)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Phone Number',
                    text: 'Please enter a valid phone number (9-15 digits without +255).\nExample: 712345678',
                    confirmButtonColor: '#dc3545'
                });
                phoneInput.focus();
                return false;
            }
            
            // Add +255 prefix to phone number
            const fullPhoneNumber = '+255' + phoneValue;
            phoneInput.value = fullPhoneNumber;
            
            // Create FormData with updated phone number
            const formData = new FormData(this);
            formData.set('phone_number', fullPhoneNumber);

            // Show loading
            Swal.fire({
                title: 'Adding Promise Guest...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Submit form via fetch to handle response
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || formData.get('_token')
                },
                redirect: 'manual' // Don't follow redirects automatically
            })
            .then(async response => {
                // Check if response is JSON
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    const data = await response.json();
                    return { ...data, status: response.status };
                } else if (response.status === 302 || response.redirected) {
                    // If redirected, it means success (Laravel redirects on success)
                    return { success: true, redirected: true };
                } else if (response.status === 422) {
                    // Validation error - try to parse as JSON
                    try {
                        const data = await response.json();
                        return { ...data, status: response.status };
                    } catch {
                        return { success: false, message: 'Validation error occurred', status: response.status };
                    }
                } else {
                    // Try to get error message from response
                    const text = await response.text();
                    try {
                        const data = JSON.parse(text);
                        return { ...data, status: response.status };
                    } catch {
                        return { success: false, message: 'An error occurred', status: response.status };
                    }
                }
            })
            .then(data => {
                if (data && data.success) {
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addPromiseGuestModal'));
                    if (modal) {
                        modal.hide();
                    }
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message || 'Promise guest added successfully!',
                        timer: 2000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    }).then(() => {
                        location.reload();
                    });
                } else if (data && data.redirected) {
                    // Handle redirect
                    location.reload();
                } else {
                    // Handle validation errors
                    let errorMessage = 'Failed to add promise guest';
                    let errorTitle = 'Error';
                    
                    if (data && data.errors) {
                        // Laravel validation errors
                        errorTitle = 'Validation Error';
                        const errors = Object.values(data.errors).flat();
                        errorMessage = errors.join('<br>');
                    } else if (data && data.message) {
                        errorMessage = data.message;
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: errorTitle,
                        html: errorMessage,
                        confirmButtonColor: '#dc3545'
                    });
                    
                    // Reset phone input to show only digits (remove +255 for display)
                    phoneInput.value = phoneValue;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while adding the promise guest. Please try again.',
                    confirmButtonColor: '#dc3545'
                });
                
                // Reset phone input to show only digits (remove +255 for display)
                phoneInput.value = phoneValue;
            });
        });
    }
});
</script>

@endsection

