@extends('layouts.index')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4">Weekly Assignments (Leader on Duty)</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('leaders.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Leaders
            </a>
            @if(auth()->user()->canManageLeadership())
                <a href="{{ route('weekly-assignments.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>New Assignment
                </a>
            @endif
        </div>
    </div>


    <!-- Filters -->
    <form method="GET" action="{{ route('weekly-assignments.index') }}" class="card mb-3">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label">Position</label>
                    <select name="position" class="form-select">
                        <option value="">All Positions</option>
                        @foreach($positions as $key => $label)
                            <option value="{{ $key }}" {{ request('position') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Assignments</option>
                        <option value="active" {{ request('status') == 'active' || !request('status') ? 'selected' : '' }}>Active Only</option>
                        <option value="current" {{ request('status') == 'current' ? 'selected' : '' }}>Current Week</option>
                        <option value="past" {{ request('status') == 'past' ? 'selected' : '' }}>Past</option>
                        <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">From Date</label>
                    <input type="date" name="from" value="{{ request('from') }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label">To Date</label>
                    <input type="date" name="to" value="{{ request('to') }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('weekly-assignments.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-times me-2"></i>Clear
                    </a>
                </div>
            </div>
        </div>
    </form>

    <!-- Assignments Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Week</th>
                            <th>Leader</th>
                            <th>Position</th>
                            <th>Duties</th>
                            <th>Status</th>
                            <th>Assigned By</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assignments as $assignment)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $assignment->week_start_date->format('M d') }} - {{ $assignment->week_end_date->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ $assignment->week_start_date->format('l') }} to {{ $assignment->week_end_date->format('l') }}</small>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $assignment->leader->member->full_name }}</div>
                                    <small class="text-muted">{{ $assignment->leader->member->member_id }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $assignment->position_display }}</span>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 200px;" title="{{ $assignment->duties }}">
                                        {{ $assignment->duties ? \Illuminate\Support\Str::limit($assignment->duties, 50) : '—' }}
                                    </div>
                                </td>
                                <td>
                                    @if($assignment->is_active)
                                        @php
                                            $today = now()->toDateString();
                                            $isCurrent = $assignment->week_start_date <= $today && $assignment->week_end_date >= $today;
                                            $isPast = $assignment->week_end_date < $today;
                                            $isFuture = $assignment->week_start_date > $today;
                                        @endphp
                                        @if($isCurrent)
                                            <span class="badge bg-success">Current</span>
                                        @elseif($isPast)
                                            <span class="badge bg-secondary">Past</span>
                                        @else
                                            <span class="badge bg-info">Upcoming</span>
                                        @endif
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $assignment->assignedBy->name ?? 'System' }}</small>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('weekly-assignments.show', $assignment) }}" class="btn btn-outline-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(auth()->user()->canManageLeadership())
                                            <a href="{{ route('weekly-assignments.edit', $assignment) }}" class="btn btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('weekly-assignments.destroy', $assignment) }}" method="POST" class="d-inline delete-assignment-form" data-assignment-id="{{ $assignment->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-calendar-times fa-3x mb-3"></i>
                                        <p>No weekly assignments found.</p>
                                        @if(auth()->user()->canManageLeadership())
                                            <a href="{{ route('weekly-assignments.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i>Create First Assignment
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($assignments->hasPages())
            <div class="card-footer">
                {{ $assignments->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show success message with SweetAlert
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    // Handle delete confirmation with SweetAlert
    document.querySelectorAll('.delete-assignment-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Delete Assignment?',
                text: 'This action cannot be undone. Are you sure you want to delete this weekly assignment?',
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
});
</script>
@endsection

