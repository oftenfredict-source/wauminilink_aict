@extends('layouts.index')

@section('content')
<div class="container-fluid px-4">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h2 class="card-title mb-1">Welcome back, Secretary!</h2>
                            <p class="card-text mb-0">Here's what's happening with your church management system today.</p>
                            <div class="mt-2">
                                <small class="text-white-50">
                                    <span id="current-date"></span> - <span id="current-time"></span>
                                </small>
                            </div>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-user-tie fa-3x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        /* Fix dashboard card header visibility */
        .card-header {
            background-color: #f8f9fa !important;
            color: #495057 !important;
            font-weight: 600 !important;
            border-bottom: 1px solid #dee2e6 !important;
        }
        
        .card-header i {
            color: #007bff !important;
        }
        
        /* Ensure all text in cards is visible */
        .card-body {
            color: #212529 !important;
        }
        
        .card-body h5 {
            color: #495057 !important;
            font-weight: 600 !important;
        }
        
        .card-body p {
            color: #6c757d !important;
        }
        
        .card-body ul li {
            color: #495057 !important;
        }
        
        /* Ensure welcome section text is white */
        .card.bg-primary .card-body {
            color: white !important;
        }
        
        .card.bg-primary .card-title {
            color: white !important;
        }
        
        .card.bg-primary .card-text {
            color: white !important;
        }
        
        /* Ensure statistics cards text is white */
        .card.bg-primary .card-body,
        .card.bg-success .card-body,
        .card.bg-warning .card-body,
        .card.bg-info .card-body {
            color: white !important;
        }
        
        .card.bg-primary .h4,
        .card.bg-success .h4,
        .card.bg-warning .h4,
        .card.bg-info .h4 {
            color: white !important;
        }
        
        .card.bg-primary .small,
        .card.bg-success .small,
        .card.bg-warning .small,
        .card.bg-info .small {
            color: rgba(255, 255, 255, 0.8) !important;
        }
        
        .card.bg-primary .text-white-50,
        .card.bg-success .text-white-50,
        .card.bg-warning .text-white-50,
        .card.bg-info .text-white-50 {
            color: rgba(255, 255, 255, 0.8) !important;
        }
    </style>
    

    <h1 class="mt-4"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>

    <!-- Dashboard Statistics -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="small text-white-50">Total Family Members</div>
                            <div class="h4 mb-0">{{ number_format($totalMembers) }}</div>
                            <div class="small text-white-50 mt-1">
                                <i class="fas fa-users me-1"></i>{{ number_format($registeredMembers) }} registered
                            </div>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-users fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('members.view') }}">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="small text-white-50">Active Events</div>
                            <div class="h4 mb-0">{{ number_format($activeEvents) }}</div>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-calendar-alt fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('special.events.index') }}">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="small text-white-50">Upcoming Celebrations</div>
                            <div class="h4 mb-0">{{ number_format($upcomingCelebrations) }}</div>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-birthday-cake fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('celebrations.index') }}">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="small text-white-50">Sunday Services</div>
                            <div class="h4 mb-0">Active</div>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-church fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('services.sunday.index') }}">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-bolt me-1"></i>
                    Quick Actions
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('members.add') }}" class="btn btn-primary w-100">
                                <i class="fas fa-user-plus me-2"></i>Add New Member
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('special.events.index') }}?action=add" class="btn btn-success w-100">
                                <i class="fas fa-calendar-plus me-2"></i>Add Special Event
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('celebrations.index') }}?action=add" class="btn btn-warning w-100">
                                <i class="fas fa-gift me-2"></i>Add Celebration
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('services.sunday.index') }}" class="btn btn-info w-100">
                                <i class="fas fa-church me-2"></i>Sunday Services
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Member Demographics Statistics -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                    Member Demographics
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Male Members -->
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                                        <i class="fas fa-male fa-2x"></i>
                                    </div>
                                    <h4 class="text-primary mb-1">{{ number_format($maleMembers) }}</h4>
                                    <p class="text-muted mb-0">Male Members</p>
                                    <small class="text-muted">{{ $totalMembers > 0 ? round(($maleMembers / $totalMembers) * 100, 1) : 0 }}% of total</small>
                                </div>
                            </div>
                        </div>

                        <!-- Female Members -->
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <div class="bg-pink text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; background-color: #e91e63 !important;">
                                        <i class="fas fa-female fa-2x"></i>
                                    </div>
                                    <h4 class="mb-1" style="color: #e91e63;">{{ number_format($femaleMembers) }}</h4>
                                    <p class="text-muted mb-0">Female Members</p>
                                    <small class="text-muted">{{ $totalMembers > 0 ? round(($femaleMembers / $totalMembers) * 100, 1) : 0 }}% of total</small>
                                </div>
                            </div>
                        </div>

                        <!-- Children -->
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                                        <i class="fas fa-child fa-2x"></i>
                                    </div>
                                    <h4 class="text-warning mb-1">{{ number_format($totalChildren) }}</h4>
                                    <p class="text-muted mb-0">Children</p>
                                    <small class="text-muted">Under 18 years</small>
                                </div>
                            </div>
                        </div>

                        <!-- Adults -->
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                                        <i class="fas fa-user-tie fa-2x"></i>
                                    </div>
                                    <h4 class="text-success mb-1">{{ number_format($adultMembers) }}</h4>
                                    <p class="text-muted mb-0">Adult Members</p>
                                    <small class="text-muted">18+ years old</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Family Breakdown -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h6 class="card-title mb-3">
                                        <i class="fas fa-family me-2"></i>Family Member Breakdown
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary rounded me-2" style="width: 20px; height: 20px;"></div>
                                                <div>
                                                    <div class="small fw-bold">Registered Members</div>
                                                    <div class="small text-muted">{{ $familyBreakdown['registered_males'] }} male, {{ $familyBreakdown['registered_females'] }} female</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-success rounded me-2" style="width: 20px; height: 20px;"></div>
                                                <div>
                                                    <div class="small fw-bold">Spouses</div>
                                                    <div class="small text-muted">{{ $familyBreakdown['spouse_males'] }} male, {{ $familyBreakdown['spouse_females'] }} female</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-warning rounded me-2" style="width: 20px; height: 20px;"></div>
                                                <div>
                                                    <div class="small fw-bold">Children</div>
                                                    <div class="small text-muted">{{ $familyBreakdown['child_males'] }} male, {{ $familyBreakdown['child_females'] }} female</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Demographics Chart -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h6 class="card-title mb-3">
                                        <i class="fas fa-chart-bar me-2"></i>Family Member Distribution
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="bg-primary rounded me-2" style="width: 20px; height: 20px;"></div>
                                                <span class="small">Male: {{ $maleMembers }} ({{ $totalMembers > 0 ? round(($maleMembers / $totalMembers) * 100, 1) : 0 }}%)</span>
                                            </div>
                                            <div class="progress mb-3" style="height: 8px;">
                                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $totalMembers > 0 ? ($maleMembers / $totalMembers) * 100 : 0 }}%"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="rounded me-2" style="width: 20px; height: 20px; background-color: #e91e63;"></div>
                                                <span class="small">Female: {{ $femaleMembers }} ({{ $totalMembers > 0 ? round(($femaleMembers / $totalMembers) * 100, 1) : 0 }}%)</span>
                                            </div>
                                            <div class="progress mb-3" style="height: 8px;">
                                                <div class="progress-bar" role="progressbar" style="width: {{ $totalMembers > 0 ? ($femaleMembers / $totalMembers) * 100 : 0 }}%; background-color: #e91e63;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="bg-warning rounded me-2" style="width: 20px; height: 20px;"></div>
                                                <span class="small">Children: {{ $totalChildren }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="bg-success rounded me-2" style="width: 20px; height: 20px;"></div>
                                                <span class="small">Adults: {{ $adultMembers }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Welcome Message -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle me-1"></i>
                    Welcome to Waumini Link
                </div>
                <div class="card-body">
                    <h5>Church Management System</h5>
                    <p class="mb-0">
                        Welcome to the Waumini Link church management system. Use the navigation menu on the left to access different sections:
                    </p>
                    <ul class="mt-3">
                        <li><strong>Members:</strong> Manage church members, add new members, view member details, and export member data.</li>
                        <li><strong>Services:</strong> Manage Sunday services and special events.</li>
                        <li><strong>Celebrations:</strong> Track member birthdays, anniversaries, and other celebrations.</li>
                        <li><strong>Finance:</strong> Manage church finances including tithes, offerings, donations, pledges, budgets, and expenses.</li>
                        <li><strong>Reports:</strong> Generate various financial and membership reports.</li>
                        <li><strong>Settings:</strong> Configure system settings and preferences.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


