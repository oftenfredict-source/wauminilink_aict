@extends('layouts.index')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm dashboard-header" style="background:white;">
                <div class="card-body py-2 px-3">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center border border-primary border-2" style="width:48px; height:48px; background:rgba(0,123,255,.1);">
                                <i class="fas fa-plus text-primary"></i>
                            </div>
                            <div class="lh-sm">
                                <h5 class="mb-0 fw-semibold text-dark">Create Announcement</h5>
                                <small class="text-muted">Add a new announcement for members</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h6><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</h6>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('announcements.store') }}">
                @csrf

                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" 
                            class="form-control @error('title') is-invalid @enderror" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="general" {{ old('type') == 'general' ? 'selected' : '' }}>General</option>
                            <option value="urgent" {{ old('type') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            <option value="event" {{ old('type') == 'event' ? 'selected' : '' }}>Event</option>
                            <option value="reminder" {{ old('type') == 'reminder' ? 'selected' : '' }}>Reminder</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Content <span class="text-danger">*</span></label>
                        <textarea name="content" rows="6" class="form-control @error('content') is-invalid @enderror" 
                            placeholder="Enter announcement content..." required>{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}" 
                            class="form-control @error('start_date') is-invalid @enderror">
                        <small class="text-muted">Leave empty for immediate publication</small>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}" 
                            class="form-control @error('end_date') is-invalid @enderror">
                        <small class="text-muted">Leave empty for no expiry</small>
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" 
                                {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active (Visible to members)
                            </label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_pinned" id="is_pinned" 
                                {{ old('is_pinned') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_pinned">
                                Pin to top (Show at the top of announcements)
                            </label>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Target Audience <span class="text-danger">*</span></label>
                        <div class="card border-light bg-light">
                            <div class="card-body">
                                <div class="d-flex gap-4 mb-3 flex-wrap">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="target_type" id="target_all" value="all"
                                            {{ old('target_type', 'all') == 'all' ? 'checked' : '' }} onchange="toggleTargeting()">
                                        <label class="form-check-label fw-semibold" for="target_all">
                                            <i class="fas fa-globe me-1 text-success"></i> All Members
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="target_type" id="target_department" value="department"
                                            {{ old('target_type') == 'department' ? 'checked' : '' }} onchange="toggleTargeting()">
                                        <label class="form-check-label fw-semibold" for="target_department">
                                            <i class="fas fa-layer-group me-1 text-primary"></i> By Department
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="target_type" id="target_specific" value="specific"
                                            {{ old('target_type') == 'specific' ? 'checked' : '' }} onchange="toggleTargeting()">
                                        <label class="form-check-label fw-semibold" for="target_specific">
                                            <i class="fas fa-user-check me-1 text-warning"></i> Specific Members
                                        </label>
                                    </div>
                                </div>

                                {{-- DEPARTMENT PICKER --}}
                                <div id="department_container" style="display: {{ old('target_type') == 'department' ? 'block' : 'none' }};">
                                    <div class="mb-3">
                                        <label for="department_id" class="form-label fw-bold">Select Department</label>
                                        <select class="form-select" id="department_id" name="department_id" onchange="showDepartmentMembers()">
                                            <option value="">-- Choose a department --</option>
                                            @foreach($departments as $dept)
                                                <option value="{{ $dept->id }}"
                                                    data-members="{{ $dept->members->pluck('full_name', 'id')->toJson() }}"
                                                    {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                                    {{ $dept->name }} ({{ $dept->members->count() }} member{{ $dept->members->count() != 1 ? 's' : '' }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Only active departments with assigned members are shown.</small>
                                    </div>
                                    {{-- Preview of department members --}}
                                    <div id="dept_members_preview" class="border rounded bg-white p-3" style="display:none; max-height: 200px; overflow-y: auto;">
                                        <p class="text-muted small mb-2 fw-bold"><i class="fas fa-users me-1"></i>Members who will receive this announcement:</p>
                                        <div id="dept_members_list" class="d-flex flex-wrap gap-1"></div>
                                    </div>
                                </div>

                                {{-- SPECIFIC MEMBERS PICKER --}}
                                <div id="specific_members_container" style="display: {{ old('target_type') == 'specific' ? 'block' : 'none' }};">
                                    <div class="mb-2">
                                        <input type="text" id="member_search" class="form-control mb-2" placeholder="Search members by name or ID..." onkeyup="filterMemberSelection()">
                                    </div>
                                    <div class="member-list-container border rounded bg-white p-2" style="max-height: 300px; overflow-y: auto;">
                                        <div class="row g-2" id="member_selection_list">
                                            @foreach($members as $member)
                                                <div class="col-md-6 col-lg-4 member-selection-item" data-name="{{ strtolower($member->full_name) }}" data-id="{{ strtolower($member->member_id) }}">
                                                    <div class="form-check border rounded p-2 px-4 h-100">
                                                        <input class="form-check-input" type="checkbox" name="target_member_ids[]"
                                                            value="{{ $member->id }}" id="member_{{ $member->id }}"
                                                            {{ is_array(old('target_member_ids')) && in_array($member->id, old('target_member_ids')) ? 'checked' : '' }}>
                                                        <label class="form-check-label d-block cursor-pointer" for="member_{{ $member->id }}">
                                                            <div class="fw-bold text-truncate">{{ $member->full_name }}</div>
                                                            <small class="text-muted">{{ $member->member_id }}</small>
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="selectVisibleMembers(true)">Select Visible</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="selectVisibleMembers(false)">Deselect Visible</button>
                                        <small class="text-muted ms-2" id="selection_count">0 selected</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-12">
                        <div class="card border-info">
                            <div class="card-body">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="send_sms" id="send_sms" 
                                        {{ old('send_sms') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="send_sms">
                                        <i class="fas fa-sms text-info me-2"></i>Send SMS notification to targeted members
                                    </label>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    <i class="fas fa-info-circle me-1"></i>
                                    If checked, an SMS will be sent to the selected target audience when this announcement is created.
                                    SMS notifications must be enabled in system settings.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('announcements.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Create Announcement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleTargeting() {
    const isDept     = document.getElementById('target_department').checked;
    const isSpecific = document.getElementById('target_specific').checked;

    document.getElementById('department_container').style.display    = isDept     ? 'block' : 'none';
    document.getElementById('specific_members_container').style.display = isSpecific ? 'block' : 'none';

    if (isDept) showDepartmentMembers();
    updateSelectionCount();
}

function showDepartmentMembers() {
    const select  = document.getElementById('department_id');
    const option  = select.selectedOptions[0];
    const preview = document.getElementById('dept_members_preview');
    const list    = document.getElementById('dept_members_list');

    if (!option || !option.value) {
        preview.style.display = 'none';
        return;
    }

    const members = JSON.parse(option.dataset.members || '{}');
    const names   = Object.values(members);

    if (names.length === 0) {
        list.innerHTML = '<span class="text-muted fst-italic">No members assigned to this department.</span>';
    } else {
        list.innerHTML = names.map(n =>
            `<span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1">${n}</span>`
        ).join('');
    }
    preview.style.display = 'block';
}

function filterMemberSelection() {
    const searchTerm = document.getElementById('member_search').value.toLowerCase();
    document.querySelectorAll('.member-selection-item').forEach(item => {
        const match = item.dataset.name.includes(searchTerm) || item.dataset.id.includes(searchTerm);
        item.style.display = match ? 'block' : 'none';
    });
}

function selectVisibleMembers(select) {
    document.querySelectorAll('.member-selection-item').forEach(item => {
        if (item.style.display !== 'none') {
            const cb = item.querySelector('.form-check-input');
            if (cb) cb.checked = select;
        }
    });
    updateSelectionCount();
}

function updateSelectionCount() {
    const checked = document.querySelectorAll('input[name="target_member_ids[]"]:checked').length;
    document.getElementById('selection_count').textContent = `${checked} selected`;
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('input[name="target_member_ids[]"]').forEach(cb => {
        cb.addEventListener('change', updateSelectionCount);
    });
    updateSelectionCount();

    // If department was pre-selected (e.g. old() after validation fail), show preview
    if (document.getElementById('target_department').checked) {
        showDepartmentMembers();
    }
});
</script>
@endsection

