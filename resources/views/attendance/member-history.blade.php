@extends('layouts.index')

@section('title', 'Member Attendance History')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-clock"></i> Attendance History - {{ $member->full_name }}
                    </h6>
                    <div class="btn-group" role="group">
                        <a href="{{ route('attendance.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Recording
                        </a>
                        <a href="{{ route('attendance.statistics') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-chart-bar"></i> Statistics
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Member Information -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Member Information</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Name:</strong> {{ $member->full_name }}</p>
                                            <p class="mb-1"><strong>Member ID:</strong> {{ $member->member_id }}</p>
                                            <p class="mb-1"><strong>Phone:</strong> {{ $member->phone_number }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Gender:</strong> {{ ucfirst($member->gender) }}</p>
                                            <p class="mb-1"><strong>Email:</strong> {{ $member->email ?? 'N/A' }}</p>
                                            <p class="mb-1"><strong>Member Type:</strong> {{ ucfirst($member->member_type) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h4 class="card-title">{{ $totalAttendances }}</h4>
                                    <p class="card-text mb-0">Total Attendances</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ $sundayAttendances }}</h5>
                                    <p class="card-text mb-0">Sunday Services</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ $specialEventAttendances }}</h5>
                                    <p class="card-text mb-0">Special Events</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">
                                        {{ $totalAttendances > 0 ? round(($sundayAttendances / $totalAttendances) * 100, 1) : 0 }}%
                                    </h5>
                                    <p class="card-text mb-0">Sunday Service %</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form method="GET" class="row g-3">
                                <div class="col-md-3">
                                    <label for="from" class="form-label">From Date</label>
                                    <input type="date" class="form-control" id="from" name="from" value="{{ request('from') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="to" class="form-label">To Date</label>
                                    <input type="date" class="form-control" id="to" name="to" value="{{ request('to') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="service_type" class="form-label">Service Type</label>
                                    <select class="form-select" id="service_type" name="service_type">
                                        <option value="">All Services</option>
                                        <option value="sunday_service" {{ request('service_type') === 'sunday_service' ? 'selected' : '' }}>
                                            Sunday Services
                                        </option>
                                        <option value="special_event" {{ request('service_type') === 'special_event' ? 'selected' : '' }}>
                                            Special Events
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-filter"></i> Filter
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Attendance Records -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold">Attendance Records</h6>
                        </div>
                        <div class="card-body">
                            @if($attendances->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Date</th>
                                                <th>Service Type</th>
                                                <th>Service Details</th>
                                                <th>Recorded By</th>
                                                <th>Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($attendances as $attendance)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $attendance->attended_at->format('M d, Y') }}</strong><br>
                                                        <small class="text-muted">{{ $attendance->attended_at->format('g:i A') }}</small>
                                                    </td>
                                                    <td>
                                                        @if($attendance->service_type === 'sunday_service')
                                                            <span class="badge bg-success">Sunday Service</span>
                                                        @else
                                                            <span class="badge bg-info">Special Event</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @php
                                                            $service = $attendance->getService();
                                                        @endphp
                                                        @if($attendance->service_type === 'sunday_service' && $service)
                                                            <strong>{{ $service->theme ?? 'General Service' }}</strong><br>
                                                            <small class="text-muted">
                                                                Preacher: {{ $service->preacher ?? 'N/A' }}
                                                            </small>
                                                        @elseif($attendance->service_type === 'special_event' && $service)
                                                            <strong>{{ $service->title }}</strong><br>
                                                            <small class="text-muted">
                                                                Speaker: {{ $service->speaker ?? 'N/A' }}
                                                            </small>
                                                        @else
                                                            <span class="text-muted">Service details not available</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $attendance->recorded_by ?? 'System' }}</td>
                                                    <td>{{ $attendance->notes ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <div class="d-flex justify-content-center">
                                    {{ $attendances->links() }}
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No attendance records found</h5>
                                    <p class="text-muted">
                                        @if(request()->hasAny(['from', 'to', 'service_type']))
                                            No records match your filter criteria.
                                        @else
                                            This member hasn't attended any services yet.
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
