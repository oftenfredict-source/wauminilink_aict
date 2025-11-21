@extends('layouts.index')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4">Edit Weekly Assignment</h1>
        <a href="{{ route('weekly-assignments.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('weekly-assignments.update', $weeklyAssignment) }}" id="assignmentForm">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Position <span class="text-danger">*</span></label>
                        <select name="position" id="position" class="form-select @error('position') is-invalid @enderror" required>
                            <option value="">Select Position</option>
                            @foreach($positions as $key => $label)
                                <option value="{{ $key }}" {{ old('position', $weeklyAssignment->position) == $key ? 'selected' : '' }}>{{ $label }}</option>
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
                                        <option value="{{ $leader->id }}" 
                                            data-position="{{ $leader->position }}"
                                            {{ old('leader_id', $weeklyAssignment->leader_id) == $leader->id ? 'selected' : '' }}>
                                            {{ $leader->member->full_name }} ({{ $leader->member->member_id }})
                                        </option>
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
                        <input type="date" name="week_start_date" value="{{ old('week_start_date', $weeklyAssignment->week_start_date->toDateString()) }}" 
                            class="form-control @error('week_start_date') is-invalid @enderror" required>
                        @error('week_start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Week End Date <span class="text-danger">*</span></label>
                        <input type="date" name="week_end_date" value="{{ old('week_end_date', $weeklyAssignment->week_end_date->toDateString()) }}" 
                            class="form-control @error('week_end_date') is-invalid @enderror" required>
                        @error('week_end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Duties / Responsibilities</label>
                        <textarea name="duties" rows="4" class="form-control @error('duties') is-invalid @enderror" 
                            placeholder="Describe the duties and responsibilities for this week...">{{ old('duties', $weeklyAssignment->duties) }}</textarea>
                        @error('duties')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror" 
                            placeholder="Additional notes...">{{ old('notes', $weeklyAssignment->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" 
                                {{ old('is_active', $weeklyAssignment->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active
                            </label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('weekly-assignments.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save me-2"></i>Update Assignment
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
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
            
            // Re-enable after 5 seconds in case of error
            setTimeout(function() {
                isSubmitting = false;
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Update Assignment';
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
    });
});
</script>
@endsection

