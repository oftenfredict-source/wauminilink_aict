@extends('layouts.index')

@section('content')
<style>
    /* Mobile Responsive Styles for Leaders Page */
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
        }
        
        /* Hide button text on mobile, show only icons */
        .btn-mobile-icon-only {
            padding: 0.375rem 0.5rem !important;
        }
        .btn-mobile-icon-only .btn-text {
            display: none;
        }
        .btn-mobile-icon-only i {
            margin: 0 !important;
        }
        
        /* Header adjustments */
        h1 {
            font-size: 1.5rem;
        }
        
        /* Card header improvements */
        .card-header {
            flex-direction: column;
            align-items: flex-start !important;
        }
        .card-header .dropdown {
            margin-top: 0.5rem;
            align-self: flex-end;
        }
    }
    
    @media (max-width: 576px) {
        h1 {
            font-size: 1.25rem;
        }
        
        .btn {
            font-size: 0.875rem;
            padding: 0.375rem 0.75rem;
        }
        
        /* Overview cards full width on mobile */
        .col-xl-3.col-md-6 {
            margin-bottom: 1rem;
        }
    }
</style>

<div class="container-fluid px-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
        <h1 class="mt-4 mb-0">Church Leadership</h1>
        <div class="d-flex flex-wrap gap-2 w-100 w-md-auto">
            <a href="{{ route('weekly-assignments.index') }}" class="btn btn-warning btn-mobile-icon-only">
                <i class="fas fa-calendar-week"></i>
                <span class="btn-text ms-2 d-none d-md-inline">Weekly Assignments</span>
            </a>
            <a href="{{ route('leaders.reports') }}" class="btn btn-info btn-mobile-icon-only">
                <i class="fas fa-chart-bar"></i>
                <span class="btn-text ms-2 d-none d-md-inline">Reports</span>
            </a>
            <a href="{{ route('leaders.identity-cards.bulk') }}" class="btn btn-success btn-mobile-icon-only" target="_blank">
                <i class="fas fa-id-card"></i>
                <span class="btn-text ms-2 d-none d-md-inline">All ID Cards</span>
            </a>
            @if(auth()->user()->canManageLeadership())
                <a href="{{ route('leaders.create') }}" class="btn btn-primary btn-mobile-icon-only">
                    <i class="fas fa-plus"></i>
                    <span class="btn-text ms-2 d-none d-md-inline">Assign Position</span>
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Leadership Overview Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="small text-white-50">Total Active Leaders</div>
                            <div class="h4">{{ $leaders->count() }}</div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
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
                            <div class="small text-white-50">Pastoral Team</div>
                            <div class="h4">{{ $leadersByPosition->get('pastor', collect())->count() + $leadersByPosition->get('assistant_pastor', collect())->count() }}</div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-cross fa-2x"></i>
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
                            <div class="small text-white-50">Ministry Leaders</div>
                            <div class="h4">{{ $leadersByPosition->filter(function($group, $position) {
                                return in_array($position, ['youth_leader', 'children_leader', 'worship_leader', 'choir_leader', 'usher_leader', 'evangelism_leader', 'prayer_leader']);
                            })->flatten()->count() }}</div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-hands-helping fa-2x"></i>
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
                            <div class="small text-white-50">Administrative</div>
                            <div class="h4">{{ $leadersByPosition->get('secretary', collect())->count() + $leadersByPosition->get('treasurer', collect())->count() + $leadersByPosition->get('elder', collect())->count() }}</div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-cogs fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leadership Positions by Category -->
    @foreach($leadersByPosition as $position => $positionLeaders)
        <div class="card mb-4">
            <div class="card-header bg-primary text-white d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <h5 class="mb-0 text-white mb-2 mb-md-0">
                    <i class="fas fa-{{ $position === 'pastor' ? 'cross' : ($position === 'secretary' ? 'file-alt' : ($position === 'treasurer' ? 'dollar-sign' : 'user-tie')) }} me-2"></i>
                    {{ $positionLeaders->first()->position_display }}
                    <span class="badge bg-white text-primary ms-2 fw-bold">{{ $positionLeaders->count() }}</span>
                </h5>
                <div class="dropdown">
                    <button class="btn btn-sm btn-white text-primary dropdown-toggle fw-bold" type="button" data-bs-toggle="dropdown" style="border: 1px solid rgba(255,255,255,0.3);">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('leaders.create') }}?position={{ $position }}">
                            <i class="fas fa-plus me-2"></i>Add {{ $positionLeaders->first()->position_display }}
                        </a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($positionLeaders as $leader)
                        <div class="col-12 col-md-6 col-lg-4 mb-3">
                            <div class="card h-100 border-start border-4 border-primary">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title mb-0">{{ $leader->member->full_name }}</h6>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="{{ route('leaders.show', $leader) }}">
                                                    <i class="fas fa-eye me-2"></i>View Details
                                                </a></li>
                                                <li><a class="dropdown-item" href="{{ route('leaders.identity-card', $leader) }}" target="_blank">
                                                    <i class="fas fa-id-card me-2"></i>Generate ID Card
                                                </a></li>
                                                @if(auth()->user()->canManageLeadership())
                                                    <li><a class="dropdown-item" href="{{ route('leaders.edit', $leader) }}">
                                                        <i class="fas fa-edit me-2"></i>Edit
                                                    </a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('leaders.deactivate', $leader) }}" method="POST" class="d-inline deactivate-leader-form">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-warning">
                                                                <i class="fas fa-pause me-2"></i>Deactivate
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('leaders.destroy', $leader) }}" method="POST" class="d-inline delete-leader-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fas fa-trash me-2"></i>Remove
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                    
                                    <p class="card-text small text-muted mb-2">
                                        <i class="fas fa-id-card me-1"></i>{{ $leader->member->member_id }}
                                    </p>
                                    
                                    @if($leader->position_title)
                                        <p class="card-text small mb-2">
                                            <strong>Title:</strong> {{ $leader->position_title }}
                                        </p>
                                    @endif
                                    
                                    <p class="card-text small mb-2">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        Appointed: {{ $leader->appointment_date->format('M d, Y') }}
                                    </p>
                                    
                                    @if($leader->end_date)
                                        <p class="card-text small mb-2">
                                            <i class="fas fa-calendar-times me-1"></i>
                                            Term Ends: {{ $leader->end_date->format('M d, Y') }}
                                        </p>
                                    @endif
                                    
                                    @if($leader->appointed_by)
                                        <p class="card-text small mb-2">
                                            <i class="fas fa-user-check me-1"></i>
                                            Appointed by: {{ $leader->appointed_by }}
                                        </p>
                                    @endif
                                    
                                    @if($leader->description)
                                        <p class="card-text small text-muted">
                                            {{ Str::limit($leader->description, 100) }}
                                        </p>
                                    @endif
                                    
                                    <div class="mt-2">
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>Active
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach

    @if($leaders->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No Leadership Positions Assigned</h5>
                <p class="text-muted">Start by assigning leadership positions to church members.</p>
                <a href="{{ route('leaders.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Assign First Leadership Position
                </a>
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // SweetAlert for success messages
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end',
            timerProgressBar: true
        });
    @endif

    // SweetAlert for error messages
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
            timer: 4000,
            showConfirmButton: true,
            toast: true,
            position: 'top-end',
            timerProgressBar: true
        });
    @endif

    // Handle deactivate confirmation with SweetAlert
    document.querySelectorAll('.deactivate-leader-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const leaderName = form.closest('.card').querySelector('.card-title')?.textContent || 'this leader';
            
            Swal.fire({
                title: 'Deactivate Leadership Position?',
                html: `Are you sure you want to deactivate the leadership position for <strong>${leaderName}</strong>?<br><br>This will mark the position as inactive but will not delete the record.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, deactivate it',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Deactivating...',
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

    // Handle delete confirmation with SweetAlert
    document.querySelectorAll('.delete-leader-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const leaderName = form.closest('.card').querySelector('.card-title')?.textContent || 'this leader';
            
            Swal.fire({
                title: 'Remove Leadership Position?',
                html: `Are you sure you want to permanently remove the leadership position for <strong>${leaderName}</strong>?<br><br><span class="text-danger">This action cannot be undone!</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, remove it',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Removing...',
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
