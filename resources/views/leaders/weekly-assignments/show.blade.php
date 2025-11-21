@extends('layouts.index')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4">Weekly Assignment Details</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('weekly-assignments.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
            @if(auth()->user()->canManageLeadership())
                <a href="{{ route('weekly-assignments.edit', $weeklyAssignment) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Edit
                </a>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar-week me-2"></i>Assignment Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Week Period:</strong>
                            <div class="mt-1">
                                {{ $weeklyAssignment->week_start_date->format('F d, Y') }} - 
                                {{ $weeklyAssignment->week_end_date->format('F d, Y') }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong>
                            <div class="mt-1">
                                @if($weeklyAssignment->is_active)
                                    @php
                                        $today = now()->toDateString();
                                        $isCurrent = $weeklyAssignment->week_start_date <= $today && $weeklyAssignment->week_end_date >= $today;
                                        $isPast = $weeklyAssignment->week_end_date < $today;
                                    @endphp
                                    @if($isCurrent)
                                        <span class="badge bg-success">Current Week</span>
                                    @elseif($isPast)
                                        <span class="badge bg-secondary">Past</span>
                                    @else
                                        <span class="badge bg-info">Upcoming</span>
                                    @endif
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Leader:</strong>
                            <div class="mt-1">
                                {{ $weeklyAssignment->leader->member->full_name }}
                                <small class="text-muted">({{ $weeklyAssignment->leader->member->member_id }})</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <strong>Position:</strong>
                            <div class="mt-1">
                                <span class="badge bg-primary">{{ $weeklyAssignment->position_display }}</span>
                            </div>
                        </div>
                    </div>

                    @if($weeklyAssignment->duties)
                    <div class="mb-3">
                        <strong>Duties / Responsibilities:</strong>
                        <div class="mt-2 p-3 bg-light rounded">
                            {{ $weeklyAssignment->duties }}
                        </div>
                    </div>
                    @endif

                    @if($weeklyAssignment->notes)
                    <div class="mb-3">
                        <strong>Notes:</strong>
                        <div class="mt-2 p-3 bg-light rounded">
                            {{ $weeklyAssignment->notes }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Additional Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Assigned By:</strong>
                        <div class="mt-1">{{ $weeklyAssignment->assignedBy->name ?? 'System' }}</div>
                    </div>
                    <div class="mb-3">
                        <strong>Created:</strong>
                        <div class="mt-1">{{ $weeklyAssignment->created_at->format('M d, Y h:i A') }}</div>
                    </div>
                    <div class="mb-3">
                        <strong>Last Updated:</strong>
                        <div class="mt-1">{{ $weeklyAssignment->updated_at->format('M d, Y h:i A') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


