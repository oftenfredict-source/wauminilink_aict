@extends('layouts.index')

@section('title', 'Service Attendance Report')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-chart-line"></i> Service Attendance Report
                        </h6>
                        <div class="btn-group" role="group">
                            <a href="{{ route('attendance.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to Recording
                            </a>
                            <a href="{{ route('attendance.statistics') }}" class="btn btn-info btn-sm">
                                <i class="fas fa-chart-bar"></i> All Statistics
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Service Information -->
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Service Information</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-1"><strong>Date:</strong>
                                                    {{ $service->service_date ?? $service->event_date }}</p>
                                                <p class="mb-1"><strong>Type:</strong>
                                                    @if($serviceType === 'sunday_service')
                                                        <span class="badge bg-success">Sunday Service</span>
                                                    @else
                                                        <span class="badge bg-info">Special Event</span>
                                                    @endif
                                                </p>
                                                <p class="mb-1"><strong>Theme/Title:</strong>
                                                    {{ $service->theme ?? $service->title }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                @if($serviceType === 'sunday_service')
                                                    <p class="mb-1"><strong>Preacher:</strong> {{ $service->preacher ?? 'N/A' }}
                                                    </p>
                                                    <p class="mb-1"><strong>Start Time:</strong>
                                                        {{ $service->start_time ?? 'N/A' }}</p>
                                                    <p class="mb-1"><strong>End Time:</strong> {{ $service->end_time ?? 'N/A' }}
                                                    </p>
                                                @else
                                                    <p class="mb-1"><strong>Speaker:</strong> {{ $service->speaker ?? 'N/A' }}
                                                    </p>
                                                    <p class="mb-1"><strong>Start Time:</strong>
                                                        {{ $service->start_time ?? 'N/A' }}</p>
                                                    <p class="mb-1"><strong>End Time:</strong> {{ $service->end_time ?? 'N/A' }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <h4 class="card-title">{{ $attendedMembers }}</h4>
                                        <p class="card-text mb-0">Members Attended</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Statistics Cards -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">{{ $attendedMembers }}</h5>
                                        <p class="card-text mb-0">Attended</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">{{ $totalMembers - $attendedMembers }}</h5>
                                        <p class="card-text mb-0">Absent</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">{{ $attendancePercentage }}%</h5>
                                        <p class="card-text mb-0">Attendance Rate</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-secondary text-white">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">{{ $totalMembers }}</h5>
                                        <p class="card-text mb-0">Total Members</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Attendance by Gender -->
                        @if($attendanceByGender->count() > 0)
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="m-0 font-weight-bold">Attendance by Gender</h6>
                                        </div>
                                        <div class="card-body">
                                            @foreach($attendanceByGender as $gender => $count)
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="text-capitalize">{{ $gender }}</span>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress me-2" style="width: 100px; height: 20px;">
                                                            <div class="progress-bar {{ $gender === 'male' ? 'bg-primary' : 'bg-pink' }}"
                                                                style="width: {{ $attendedMembers > 0 ? ($count / $attendedMembers) * 100 : 0 }}%">
                                                            </div>
                                                        </div>
                                                        <span class="badge bg-secondary">{{ $count }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="m-0 font-weight-bold">Attendance Summary</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row text-center">
                                                <div class="col-6">
                                                    <h4 class="text-success">{{ $attendedMembers }}</h4>
                                                    <p class="mb-0">Present</p>
                                                </div>
                                                <div class="col-6">
                                                    <h4 class="text-danger">{{ $totalMembers - $attendedMembers }}</h4>
                                                    <p class="mb-0">Absent</p>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="text-center">
                                                <h5 class="text-primary">{{ $attendancePercentage }}%</h5>
                                                <p class="mb-0">Attendance Rate</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Attendees List -->
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold">Attendees List ({{ $attendances->count() }} members)</h6>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-primary" onclick="exportToCSV()">
                                        <i class="fas fa-download"></i> Export CSV
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="printReport()">
                                        <i class="fas fa-print"></i> Print
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                @if($attendances->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover" id="attendeesTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Member Name</th>
                                                    <th>Env No</th>
                                                    <th>Member ID</th>
                                                    <th>Gender</th>
                                                    <th>Phone</th>
                                                    <th>Attended At</th>
                                                    <th>Recorded By</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($attendances as $index => $attendance)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>
                                                            <strong>{{ $attendance->member->full_name }}</strong>
                                                        </td>
                                                        <td>{{ $attendance->member->envelope_number ?? 'N/A' }}</td>
                                                        <td>{{ $attendance->member->member_id }}</td>
                                                        <td>
                                                            <span
                                                                class="badge {{ $attendance->member->gender === 'male' ? 'bg-primary' : 'bg-pink' }}">
                                                                {{ ucfirst($attendance->member->gender) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $attendance->member->phone_number }}</td>
                                                        <td>
                                                            {{ $attendance->attended_at->format('M d, Y g:i A') }}
                                                        </td>
                                                        <td>{{ $attendance->recorded_by ?? 'System' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No attendees recorded</h5>
                                        <p class="text-muted">No members have been marked as present for this service.</p>
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

@section('scripts')
    <script>
        function exportToCSV() {
            const table = document.getElementById('attendeesTable');
            const rows = table.querySelectorAll('tr');
            let csv = [];

            for (let i = 0; i < rows.length; i++) {
                const row = [];
                const cols = rows[i].querySelectorAll('td, th');

                for (let j = 0; j < cols.length; j++) {
                    let cellText = cols[j].innerText.replace(/,/g, ';');
                    row.push('"' + cellText + '"');
                }
                csv.push(row.join(','));
            }

            const csvContent = csv.join('\n');
            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'attendance_report_{{ $service->service_date ?? $service->event_date }}.csv';
            a.click();
            window.URL.revokeObjectURL(url);
        }

        function printReport() {
            window.print();
        }
    </script>
@endsection