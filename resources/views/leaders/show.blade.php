@extends('layouts.index')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4">Leadership Position Details</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('leaders.edit', $leader) }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            <a href="{{ route('leaders.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Leaders
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-user-tie me-2"></i>{{ $leader->position_display }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Member Information</h6>
                            <div class="mb-3">
                                <strong>Name:</strong><br>
                                {{ $leader->member->full_name }}
                            </div>
                            <div class="mb-3">
                                <strong>Member ID:</strong><br>
                                {{ $leader->member->member_id }}
                            </div>
                            <div class="mb-3">
                                <strong>Phone:</strong><br>
                                {{ $leader->member->phone_number ?? 'Not provided' }}
                            </div>
                            <div class="mb-3">
                                <strong>Email:</strong><br>
                                {{ $leader->member->email ?? 'Not provided' }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Position Details</h6>
                            <div class="mb-3">
                                <strong>Position:</strong><br>
                                {{ $leader->position_display }}
                                @if($leader->position_title)
                                    <br><small class="text-muted">{{ $leader->position_title }}</small>
                                @endif
                            </div>
                            <div class="mb-3">
                                <strong>Status:</strong><br>
                                <span class="badge bg-{{ $leader->is_active ? 'success' : 'secondary' }} fs-6">
                                    <i class="fas fa-{{ $leader->is_active ? 'check-circle' : 'pause-circle' }} me-1"></i>
                                    {{ $leader->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="mb-3">
                                <strong>Appointment Date:</strong><br>
                                {{ $leader->appointment_date->format('F d, Y') }}
                            </div>
                            @if($leader->end_date)
                                <div class="mb-3">
                                    <strong>Term Ends:</strong><br>
                                    {{ $leader->end_date->format('F d, Y') }}
                                    @if($leader->end_date < now())
                                        <br><small class="text-danger">Term has expired</small>
                                    @elseif($leader->end_date < now()->addDays(30))
                                        <br><small class="text-warning">Term expires soon</small>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($leader->description)
                        <hr>
                        <h6 class="text-muted mb-3">Description</h6>
                        <p class="mb-0">{{ $leader->description }}</p>
                    @endif

                    @if($leader->appointed_by)
                        <hr>
                        <h6 class="text-muted mb-3">Appointment Information</h6>
                        <div class="mb-0">
                            <strong>Appointed by:</strong> {{ $leader->appointed_by }}
                        </div>
                    @endif

                    @if($leader->notes)
                        <hr>
                        <h6 class="text-muted mb-3">Notes</h6>
                        <p class="mb-0">{{ $leader->notes }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0 text-white">
                        <i class="fas fa-cogs me-2"></i>Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('leaders.identity-card', $leader) }}" class="btn btn-info" target="_blank">
                            <i class="fas fa-id-card me-2"></i>Generate ID Card
                        </a>
                        <a href="{{ route('leaders.edit', $leader) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Edit Position
                        </a>
                        
                        @if($leader->is_active)
                            <form action="{{ route('leaders.deactivate', $leader) }}" method="POST" class="d-grid">
                                @csrf
                                <button type="submit" class="btn btn-warning" 
                                        onclick="return confirm('Are you sure you want to deactivate this leadership position?')">
                                    <i class="fas fa-pause me-2"></i>Deactivate
                                </button>
                            </form>
                        @else
                            <form action="{{ route('leaders.reactivate', $leader) }}" method="POST" class="d-grid">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-play me-2"></i>Reactivate
                                </button>
                            </form>
                        @endif
                        
                        <hr>
                        <form action="{{ route('leaders.destroy', $leader) }}" method="POST" class="d-grid">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" 
                                    onclick="return confirm('Are you sure you want to permanently remove this leadership position? This action cannot be undone.')">
                                <i class="fas fa-trash me-2"></i>Remove Position
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0 text-white">
                        <i class="fas fa-history me-2"></i>Position History
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Position Assigned</h6>
                                <p class="timeline-text small text-muted">
                                    {{ $leader->appointment_date->format('M d, Y') }}
                                    @if($leader->appointed_by)
                                        <br>by {{ $leader->appointed_by }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        @if($leader->end_date)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-{{ $leader->end_date < now() ? 'danger' : 'info' }}"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Term End Date</h6>
                                    <p class="timeline-text small text-muted">
                                        {{ $leader->end_date->format('M d, Y') }}
                                        @if($leader->end_date < now())
                                            <br><span class="text-danger">Expired</span>
                                        @elseif($leader->end_date < now()->addDays(30))
                                            <br><span class="text-warning">Expires Soon</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endif
                        
                        <div class="timeline-item">
                            <div class="timeline-marker bg-{{ $leader->is_active ? 'success' : 'secondary' }}"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Current Status</h6>
                                <p class="timeline-text small text-muted">
                                    {{ $leader->is_active ? 'Active' : 'Inactive' }}
                                    <br>Last updated: {{ $leader->updated_at->format('M d, Y g:i A') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0 text-white">
                        <i class="fas fa-info-circle me-2"></i>Quick Info
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <div class="h5 mb-0">{{ $leader->member->leadershipPositions()->count() }}</div>
                                <div class="small text-muted">Total Positions</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="h5 mb-0">{{ $leader->member->activeLeadershipPositions()->count() }}</div>
                            <div class="small text-muted">Active Positions</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-content {
    padding-left: 10px;
}

.timeline-title {
    margin-bottom: 5px;
    font-size: 0.9rem;
    font-weight: 600;
}

.timeline-text {
    margin-bottom: 0;
}
</style>
@endsection
