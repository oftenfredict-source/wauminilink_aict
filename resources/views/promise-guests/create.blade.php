@extends('layouts.index')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between mt-4 mb-3 gap-2">
        <h2 class="mb-0">Add Promise Guest</h2>
        <a href="{{ route('promise-guests.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('promise-guests.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Guest Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="phone_number" class="form-label">Phone Number <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">+255</span>
                            <input type="text" class="form-control @error('phone_number') is-invalid @enderror" 
                                   id="phone_number" name="phone_number" 
                                   placeholder="712345678" 
                                   value="{{ old('phone_number') }}" 
                                   pattern="[0-9]{9,15}" 
                                   maxlength="15"
                                   required>
                        </div>
                        <small class="text-muted">Enter phone number without +255 (e.g., 712345678). Required for SMS notifications.</small>
                        @error('phone_number')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email (Optional)</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="promised_service_date" class="form-label">Promised Service Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('promised_service_date') is-invalid @enderror" 
                               id="promised_service_date" name="promised_service_date" 
                               value="{{ old('promised_service_date') }}" 
                               min="{{ date('Y-m-d') }}" required>
                        @error('promised_service_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="service_id" class="form-label">Sunday Service (Optional)</label>
                        <select class="form-select @error('service_id') is-invalid @enderror" 
                                id="service_id" name="service_id">
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
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Promise Guest
                    </button>
                    <a href="{{ route('promise-guests.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const phoneInput = document.getElementById('phone_number');
        const form = document.querySelector('form');
        
        // Format phone number on input (only allow digits)
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                // Remove any non-digit characters
                this.value = this.value.replace(/\D/g, '');
            });

            // Add +255 prefix before form submission
            if (form) {
                form.addEventListener('submit', function(e) {
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

        // Auto-update service date when a service is selected
        const serviceSelect = document.getElementById('service_id');
        const dateInput = document.getElementById('promised_service_date');
        
        if (serviceSelect && dateInput) {
            serviceSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.value && selectedOption.dataset.date) {
                    dateInput.value = selectedOption.dataset.date;
                }
            });

            // Auto-update service selection when date changes
            dateInput.addEventListener('change', function() {
                const selectedDate = this.value;
                
                // Try to find matching service
                for (let option of serviceSelect.options) {
                    if (option.dataset.date === selectedDate) {
                        serviceSelect.value = option.value;
                        break;
                    } else {
                        serviceSelect.value = '';
                    }
                }
            });
        }
    });
</script>

@if(session('success'))
    <script>
        toastr.success('{{ session('success') }}');
    </script>
@endif

@if(session('error'))
    <script>
        toastr.error('{{ session('error') }}');
    </script>
@endif

@endsection

