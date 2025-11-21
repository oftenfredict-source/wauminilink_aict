@extends('layouts.index')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex align-items-center justify-content-between mt-4 mb-3">
        <h2 class="mb-0">Create Bereavement Event</h2>
        <a href="{{ route('bereavement.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form id="bereavementForm">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Deceased Name / Affected Family <span class="text-danger">*</span></label>
                        <input type="text" name="deceased_name" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Incident Date <span class="text-danger">*</span></label>
                        <input type="date" name="incident_date" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Family Details</label>
                    <textarea name="family_details" class="form-control" rows="3" placeholder="Additional family information..."></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Related Departments</label>
                    <input type="text" name="related_departments" class="form-control" placeholder="e.g., Youth, Women, Men, Choir">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Contribution Start Date <span class="text-danger">*</span></label>
                        <input type="date" name="contribution_start_date" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Contribution End Date <span class="text-danger">*</span></label>
                        <input type="date" name="contribution_end_date" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="Additional notes..."></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Select Members for Contribution Tracking</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="selectAllMembers" checked>
                        <label class="form-check-label" for="selectAllMembers">
                            Include all members (leave unchecked to select specific members)
                        </label>
                    </div>
                    <div id="memberSelection" class="mt-3" style="display: none;">
                        <select name="member_ids[]" class="form-select" multiple size="10" id="memberSelect">
                            @foreach($members as $member)
                            <option value="{{ $member->id }}">{{ $member->full_name }} ({{ $member->member_id }})</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Hold Ctrl/Cmd to select multiple members</small>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="send_notifications" id="sendNotifications" checked>
                        <label class="form-check-label" for="sendNotifications">
                            Send notifications to members about this bereavement event
                        </label>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Create Event
                    </button>
                    <a href="{{ route('bereavement.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('selectAllMembers').addEventListener('change', function() {
    document.getElementById('memberSelection').style.display = this.checked ? 'none' : 'block';
    if (this.checked) {
        document.getElementById('memberSelect').selectedIndex = -1;
    }
});

document.getElementById('bereavementForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating...';
    
    const formData = new FormData(this);
    const selectAll = document.getElementById('selectAllMembers').checked;
    
    if (selectAll) {
        formData.delete('member_ids[]');
    } else {
        const selectedMembers = Array.from(document.getElementById('memberSelect').selectedOptions).map(opt => opt.value);
        formData.delete('member_ids[]');
        selectedMembers.forEach(id => formData.append('member_ids[]', id));
    }
    
    formData.append('send_notifications', document.getElementById('sendNotifications').checked ? '1' : '0');
    
    fetch('{{ route("bereavement.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                try {
                    return JSON.parse(text);
                } catch {
                    throw new Error(text || 'Server error occurred');
                }
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            window.location.href = '{{ route("bereavement.index") }}';
        } else {
            let errorMsg = data.message || 'Failed to create event';
            if (data.errors) {
                const errorList = Object.values(data.errors).flat().join('\n');
                errorMsg += '\n\n' + errorList;
            }
            alert('Error: ' + errorMsg);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred: ' + (error.message || 'Please check the console for details'));
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});
</script>
@endsection

