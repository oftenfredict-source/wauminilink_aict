@extends('layouts.index')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4">Create Weekly Assignment</h1>
        <a href="{{ route('weekly-assignments.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('weekly-assignments.store') }}" id="assignmentForm">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Position <span class="text-danger">*</span></label>
                        <select name="position" id="position" class="form-select @error('position') is-invalid @enderror" required>
                            <option value="">Select Position</option>
                            @foreach($positions as $key => $label)
                                <option value="{{ $key }}" {{ old('position') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('position')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Leader <span class="text-danger">*</span></label>
                        <select name="leader_id" id="leader_id" class="form-select @error('leader_id') is-invalid @enderror" required>
                            <option value="">Select Leader</option>
                            @foreach($leadersByPosition as $position => $leaders)
                                <optgroup label="{{ $positions[$position] ?? ucfirst(str_replace('_', ' ', $position)) }}">
                                    @foreach($leaders as $leader)
                                        @if($leader->member)
                                            <option value="{{ $leader->id }}" 
                                                data-position="{{ $leader->position }}"
                                                {{ old('leader_id') == $leader->id ? 'selected' : '' }}>
                                                {{ $leader->member->full_name }} ({{ $leader->member->member_id }})
                                            </option>
                                        @endif
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @error('leader_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Week Start Date <span class="text-danger">*</span></label>
                        <input type="date" name="week_start_date" value="{{ old('week_start_date', now()->startOfWeek()->toDateString()) }}" 
                            class="form-control @error('week_start_date') is-invalid @enderror" required>
                        @error('week_start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Week End Date <span class="text-danger">*</span></label>
                        <input type="date" name="week_end_date" value="{{ old('week_end_date', now()->endOfWeek()->toDateString()) }}" 
                            class="form-control @error('week_end_date') is-invalid @enderror" required>
                        @error('week_end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Duties / Responsibilities</label>
                        <textarea name="duties" rows="4" class="form-control @error('duties') is-invalid @enderror" 
                            placeholder="Describe the duties and responsibilities for this week...">{{ old('duties') }}</textarea>
                        @error('duties')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror" 
                            placeholder="Additional notes...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('weekly-assignments.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save me-2"></i>Create Assignment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Prevent double form submission
    const form = document.getElementById('assignmentForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (form && submitBtn) {
        let isSubmitting = false;
        
        form.addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return false;
            }
            
            isSubmitting = true;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating...';
            
            // Re-enable after 5 seconds in case of error
            setTimeout(function() {
                isSubmitting = false;
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Create Assignment';
            }, 5000);
        });
    }

    // Show success message with SweetAlert on page load if redirected from form submission
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            html: '<ul style="text-align: left; margin: 10px 0;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>'
        });
    @endif
    
    const positionSelect = document.getElementById('position');
    const leaderSelect = document.getElementById('leader_id');
    
    // Filter leaders by position when position is selected
    positionSelect.addEventListener('change', function() {
        const selectedPosition = this.value;
        const options = leaderSelect.querySelectorAll('option');
        
        options.forEach(option => {
            if (option.value === '') {
                option.style.display = '';
                return;
            }
            
            const optionPosition = option.getAttribute('data-position');
            if (selectedPosition === '' || optionPosition === selectedPosition) {
                option.style.display = '';
            } else {
                option.style.display = 'none';
            }
        });
        
        // Reset leader selection if current selection doesn't match
        if (selectedPosition && leaderSelect.value) {
            const selectedOption = leaderSelect.options[leaderSelect.selectedIndex];
            if (selectedOption.getAttribute('data-position') !== selectedPosition) {
                leaderSelect.value = '';
            }
        }
    });
    
    // Auto-select position when leader is selected
    leaderSelect.addEventListener('change', function() {
        if (this.value) {
            const selectedOption = this.options[this.selectedIndex];
            const leaderPosition = selectedOption.getAttribute('data-position');
            if (leaderPosition && !positionSelect.value) {
                positionSelect.value = leaderPosition;
                positionSelect.dispatchEvent(new Event('change'));
            }
        }
    });
});
</script>
@endsection

