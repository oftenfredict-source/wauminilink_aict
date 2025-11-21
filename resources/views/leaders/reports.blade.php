@extends('layouts.index')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4">Leadership Reports</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('leaders.export.csv') }}" class="btn btn-success">
                <i class="fas fa-file-csv me-2"></i>Export CSV
            </a>
            <a href="{{ route('leaders.export.pdf') }}" class="btn btn-danger" target="_blank">
                <i class="fas fa-file-pdf me-2"></i>Export PDF
            </a>
            <a href="{{ route('leaders.identity-cards.bulk') }}" class="btn btn-info" target="_blank">
                <i class="fas fa-id-card me-2"></i>All ID Cards
            </a>
            <a href="{{ route('leaders.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Leaders
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="small text-white-50">Total Leadership Positions</div>
                            <div class="h4">{{ $leaders->count() }}</div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-tie fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="small text-white-50">Active Positions</div>
                            <div class="h4">{{ $activeLeaders->count() }}</div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="small text-white-50">Inactive Positions</div>
                            <div class="h4">{{ $inactiveLeaders->count() }}</div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-pause-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="small text-white-50">Recent Appointments</div>
                            <div class="h4">{{ $recentAppointments->count() }}</div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-plus fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leadership by Position -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0 text-white">
                <i class="fas fa-chart-pie me-2"></i>Leadership by Position
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($leadersByPosition as $position => $positionLeaders)
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card border-start border-4 border-primary">
                            <div class="card-body">
                                <h6 class="card-title">{{ $positionLeaders->first()->position_display }}</h6>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="h4 mb-0">{{ $positionLeaders->count() }}</span>
                                    <div class="text-muted small">
                                        {{ $positionLeaders->where('is_active', true)->count() }} active
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Appointments -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0 text-white">
                <i class="fas fa-calendar-plus me-2"></i>Recent Appointments (Last 6 Months)
            </h5>
        </div>
        <div class="card-body">
            @if($recentAppointments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th>Position</th>
                                <th>Appointment Date</th>
                                <th>Status</th>
                                <th>Appointed By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentAppointments->sortByDesc('appointment_date') as $leader)
                                <tr>
                                    <td>
                                        <strong>{{ $leader->member->full_name }}</strong><br>
                                        <small class="text-muted">{{ $leader->member->member_id }}</small>
                                    </td>
                                    <td>{{ $leader->position_display }}</td>
                                    <td>{{ $leader->appointment_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $leader->is_active ? 'success' : 'secondary' }}">
                                            {{ $leader->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>{{ $leader->appointed_by ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Recent Appointments</h5>
                    <p class="text-muted">No leadership positions have been assigned in the last 6 months.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Expiring Terms -->
    @if($expiringTerms->count() > 0)
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0 text-dark">
                    <i class="fas fa-exclamation-triangle me-2"></i>Expiring Terms (Next 3 Months)
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th>Position</th>
                                <th>End Date</th>
                                <th>Days Remaining</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expiringTerms->sortBy('end_date') as $leader)
                                <tr>
                                    <td>
                                        <strong>{{ $leader->member->full_name }}</strong><br>
                                        <small class="text-muted">{{ $leader->member->member_id }}</small>
                                    </td>
                                    <td>{{ $leader->position_display }}</td>
                                    <td>{{ $leader->end_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $leader->end_date->diffInDays(now()) <= 30 ? 'danger' : 'warning' }}">
                                            {{ $leader->end_date->diffInDays(now()) }} days
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('leaders.edit', $leader) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit me-1"></i>Extend Term
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Leadership by Year -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0 text-white">
                <i class="fas fa-chart-bar me-2"></i>Leadership Appointments by Year
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($leadersByYear->sortKeys() as $year => $yearLeaders)
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="card border-start border-4 border-info">
                            <div class="card-body">
                                <h6 class="card-title">{{ $year }}</h6>
                                <div class="h4 mb-0">{{ $yearLeaders->count() }}</div>
                                <div class="text-muted small">
                                    {{ $yearLeaders->where('is_active', true)->count() }} still active
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Identity Card Generation -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0 text-white">
                <i class="fas fa-id-card me-2"></i>Identity Card Generation
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6>Generate ID Cards by Position</h6>
                    <div class="d-grid gap-2">
                        @foreach($leadersByPosition as $position => $positionLeaders)
                            <a href="{{ route('leaders.identity-cards.position', $position) }}" 
                               class="btn btn-outline-info btn-sm" target="_blank">
                                <i class="fas fa-id-card me-2"></i>{{ $positionLeaders->first()->position_display }} 
                                ({{ $positionLeaders->count() }} cards)
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-6">
                    <h6>Quick Actions</h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('leaders.identity-cards.bulk') }}" 
                           class="btn btn-info" target="_blank">
                            <i class="fas fa-id-card me-2"></i>Generate All ID Cards
                        </a>
                        <small class="text-muted">
                            Total: {{ $leaders->count() }} leadership positions
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Complete Leadership List -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0 text-white">
                <i class="fas fa-list me-2"></i>Complete Leadership Directory
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="leadershipTable">
                    <thead>
                        <tr>
                            <th>Member</th>
                            <th>Position</th>
                            <th>Appointment Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Appointed By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaders->sortBy('position') as $leader)
                            <tr>
                                <td>
                                    <strong>{{ $leader->member->full_name }}</strong><br>
                                    <small class="text-muted">{{ $leader->member->member_id }}</small>
                                </td>
                                <td>{{ $leader->position_display }}</td>
                                <td>{{ $leader->appointment_date->format('M d, Y') }}</td>
                                <td>{{ $leader->end_date ? $leader->end_date->format('M d, Y') : 'Indefinite' }}</td>
                                <td>
                                    <span class="badge bg-{{ $leader->is_active ? 'success' : 'secondary' }}">
                                        {{ $leader->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>{{ $leader->appointed_by ?? 'N/A' }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('leaders.show', $leader) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(auth()->user()->canManageLeadership())
                                            <a href="{{ route('leaders.edit', $leader) }}" class="btn btn-outline-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable if available
    if (typeof $.fn.DataTable !== 'undefined') {
        $('#leadershipTable').DataTable({
            "pageLength": 25,
            "order": [[ 2, "desc" ]],
            "columnDefs": [
                { "orderable": false, "targets": 6 }
            ]
        });
    }
});
</script>
@endsection
