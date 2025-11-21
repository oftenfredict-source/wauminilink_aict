@extends('layouts.index')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4">Assign Leadership Position</h1>
        <a href="{{ route('leaders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Leaders
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-user-plus me-2"></i>New Leadership Position
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('leaders.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="member_id" class="form-label">Select Member <span class="text-danger">*</span></label>
                                <select class="form-select @error('member_id') is-invalid @enderror" id="member_id" name="member_id" required>
                                    <option value="">Choose a member...</option>
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

                            <div class="col-md-6 mb-3">
                                <label for="position" class="form-label">Position <span class="text-danger">*</span></label>
                                <select class="form-select @error('position') is-invalid @enderror" id="position" name="position" required>
                                    <option value="">Choose a position...</option>
                                    @foreach($positions as $key => $value)
                                        <option value="{{ $key }}" {{ old('position') == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3" id="position_title_field" style="display: none;">
                            <label for="position_title" class="form-label">Custom Position Title</label>
                            <input type="text" class="form-control @error('position_title') is-invalid @enderror" 
                                   id="position_title" name="position_title" value="{{ old('position_title') }}"
                                   placeholder="Enter custom position title">
                            @error('position_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Brief description of responsibilities">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="appointment_date" class="form-label">Appointment Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('appointment_date') is-invalid @enderror" 
                                       id="appointment_date" name="appointment_date" 
                                       value="{{ old('appointment_date', date('Y-m-d')) }}" required>
                                @error('appointment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">End Date (Optional)</label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                       id="end_date" name="end_date" value="{{ old('end_date') }}">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Leave blank for indefinite term</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="appointed_by" class="form-label">Appointed By</label>
                            <input type="text" class="form-control @error('appointed_by') is-invalid @enderror" 
                                   id="appointed_by" name="appointed_by" value="{{ old('appointed_by') }}"
                                   placeholder="Who appointed this leader?">
                            @error('appointed_by')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3" 
                                      placeholder="Additional notes or comments">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('leaders.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Assign Position
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0 text-white">
                        <i class="fas fa-info-circle me-2"></i>Guidelines
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Each member can hold only one active position of the same type
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Use "Other" position for custom roles not listed
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            End date is optional for indefinite terms
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            You can deactivate positions later if needed
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0 text-white">
                        <i class="fas fa-users me-2"></i>Current Leaders
                    </h6>
                </div>
                <div class="card-body">
                    <div class="small text-muted">
                        <strong>{{ $members->count() }}</strong> total members available for leadership positions.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const positionSelect = document.getElementById('position');
    const positionTitleField = document.getElementById('position_title_field');
    
    positionSelect.addEventListener('change', function() {
        if (this.value === 'other') {
            positionTitleField.style.display = 'block';
            document.getElementById('position_title').required = true;
        } else {
            positionTitleField.style.display = 'none';
            document.getElementById('position_title').required = false;
        }
    });
    
    // Show position title field if "other" is pre-selected
    if (positionSelect.value === 'other') {
        positionTitleField.style.display = 'block';
        document.getElementById('position_title').required = true;
    }
});
</script>
@endsection
