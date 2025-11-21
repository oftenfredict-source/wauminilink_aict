@extends('layouts.index')

@section('content')
    <style>
        .logo-white-section {
            background-color: white !important;
            border-radius: 8px;
            margin: 8px 0;
            padding: 8px 16px !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .logo-white-section:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        .navbar-brand .logo {
            transition: all 0.3s ease;
        }
        .navbar-brand .logo:hover {
            transform: scale(1.05);
        }
        .navbar-brand {
            min-height: 60px;
            display: flex !important;
            align-items: center !important;
        }
        
        /* Custom sidebar styling */
        .sb-sidenav {
            background-color: #17082d !important;
        }
        
        .sb-sidenav .nav-link {
            color: white !important;
            transition: all 0.3s ease;
        }
        
        .sb-sidenav .sb-sidenav-menu-heading {
            color: rgba(255, 255, 255, 0.6) !important;
        }
        
        .sb-sidenav .sb-nav-link-icon {
            color: white !important;
        }
        
        .sb-sidenav .sb-sidenav-collapse-arrow {
            color: white !important;
        }
        
        .sb-sidenav .sb-sidenav-footer {
            background-color: rgba(255, 255, 255, 0.1) !important;
            color: white !important;
        }
        .table.interactive-table tbody tr:hover { background-color: #f8f9ff; }
        .table.interactive-table tbody tr td:first-child { border-left: 4px solid #5b2a86; }
        
        /* Celebration specific styles */
        .celebration-card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .celebration-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .celebration-card .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }
        .view-toggle-btn {
            border-radius: 20px;
            padding: 8px 16px;
            transition: all 0.3s ease;
        }
        .view-toggle-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }
        .view-toggle-btn:not(.active) {
            background: transparent;
            color: #667eea;
            border: 2px solid #667eea;
        }
        .view-toggle-btn:not(.active):hover {
            background: #667eea;
            color: white;
        }
        
        /* Layout fixes */
        #layoutSidenav { display: flex; }
        #layoutSidenav_nav { flex-shrink: 0; }
        #layoutSidenav_content { flex: 1; }
        .sb-nav-fixed #layoutSidenav #layoutSidenav_nav { position: fixed; top: 56px; left: 0; width: 225px; height: calc(100vh - 56px); z-index: 1039; }
        .sb-nav-fixed #layoutSidenav #layoutSidenav_content { padding-left: 225px; }
    </style>
<div class="container-fluid px-4">
                    <div class="d-flex flex-wrap align-items-center justify-content-between mt-4 mb-3 gap-2">
                        <h2 class="mb-0">Celebrations</h2>
                        <div class="d-flex gap-2">
                            <div class="btn-group" role="group">
                                <button class="btn btn-outline-primary view-toggle-btn active" id="listViewBtn" onclick="switchView('list')">
                                    <i class="fas fa-list me-1"></i>List View
                                </button>
                                <button class="btn btn-outline-primary view-toggle-btn" id="cardViewBtn" onclick="switchView('card')">
                                    <i class="fas fa-th-large me-1"></i>Card View
                                </button>
                            </div>
                            <a href="{{ route('celebrations.export.csv', request()->query()) }}" class="btn btn-outline-success"><i class="fas fa-file-excel me-2"></i>Export CSV</a>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCelebrationModal" onclick="openAddCelebration()"><i class="fas fa-plus me-2"></i>Add Celebration</button>
                        </div>
                    </div>

                    <form method="GET" action="{{ route('celebrations.index') }}" class="card mb-3" id="filtersForm">
                        <div class="card-body">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    <label class="form-label">Search</label>
                                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search title, celebrant, venue, type">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Type</label>
                                    <select name="type" class="form-select">
                                        <option value="">All Types</option>
                                        <option value="Birthday" {{ request('type') == 'Birthday' ? 'selected' : '' }}>Birthday</option>
                                        <option value="Anniversary" {{ request('type') == 'Anniversary' ? 'selected' : '' }}>Anniversary</option>
                                        <option value="Wedding" {{ request('type') == 'Wedding' ? 'selected' : '' }}>Wedding</option>
                                        <option value="Graduation" {{ request('type') == 'Graduation' ? 'selected' : '' }}>Graduation</option>
                                        <option value="Other" {{ request('type') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">From</label>
                                    <input type="date" name="from" value="{{ request('from') }}" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">To</label>
                                    <input type="date" name="to" value="{{ request('to') }}" class="form-control">
                                </div>
                                <div class="col-md-3 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter me-2"></i>Apply</button>
                                    <a href="{{ route('celebrations.index') }}" class="btn btn-outline-secondary"><i class="fas fa-times"></i></a>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- List View -->
                    <div id="listView">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Title</th>
                                                <th>Celebrant</th>
                                                <th>Type</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Venue</th>
                                                <th>Guests</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($celebrations as $celebration)
                                            <tr>
                                                <td>
                                                    <div class="fw-bold">{{ $celebration->title }}</div>
                                                    @if($celebration->description)
                                                        <small class="text-muted">{{ Str::limit($celebration->description, 50) }}</small>
                                                    @endif
                                                </td>
                                                <td>{{ $celebration->celebrant_name ?? '—' }}</td>
                                                <td>
                                                    @if($celebration->type)
                                                        <span class="celebration-type-badge">{{ $celebration->type }}</span>
                                                    @else
                                                        —
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="celebration-date">
                                                        {{ $celebration->celebration_date ? $celebration->celebration_date->format('M d, Y') : '—' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($celebration->start_time && $celebration->end_time)
                                                        {{ \Carbon\Carbon::parse($celebration->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($celebration->end_time)->format('g:i A') }}
                                                    @elseif($celebration->start_time)
                                                        {{ \Carbon\Carbon::parse($celebration->start_time)->format('g:i A') }}
                                                    @else
                                                        —
                                                    @endif
                                                </td>
                                                <td>{{ $celebration->venue ?? '—' }}</td>
                                                <td>{{ $celebration->expected_guests ?? '—' }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button class="btn btn-outline-info btn-sm" onclick="viewDetails({{ $celebration->id }})" title="View Details">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <button class="btn btn-outline-primary btn-sm" onclick="openEdit({{ $celebration->id }})" title="Edit Celebration">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-outline-danger btn-sm" onclick="confirmDelete({{ $celebration->id }})" title="Delete Celebration">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4">
                                                    <div class="text-muted">
                                                        <i class="fas fa-birthday-cake fa-3x mb-3"></i>
                                                        <p>No celebrations found</p>
                                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCelebrationModal" onclick="openAddCelebration()">
                                                            <i class="fas fa-plus me-2"></i>Add First Celebration
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card View -->
                    <div id="cardView" style="display: none;">
                        <div class="row">
                            @forelse($celebrations as $celebration)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card celebration-card h-100">
                                    <div class="card-header celebration-header">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 fw-bold">{{ $celebration->title }}</h6>
                                            @if($celebration->type)
                                                <span class="celebration-type-badge">{{ $celebration->type }}</span>
                                            @endif
                                        </div>
                                        @if($celebration->celebrant_name)
                                            <small class="opacity-75">{{ $celebration->celebrant_name }}</small>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <span class="celebration-date">
                                                {{ $celebration->celebration_date ? $celebration->celebration_date->format('M d, Y') : '—' }}
                                            </span>
                                        </div>
                                        @if($celebration->start_time && $celebration->end_time)
                                            <p class="mb-2">
                                                <i class="fas fa-clock me-2 text-primary"></i>
                                                {{ \Carbon\Carbon::parse($celebration->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($celebration->end_time)->format('g:i A') }}
                                            </p>
                                        @endif
                                        @if($celebration->venue)
                                            <p class="mb-2">
                                                <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                                {{ $celebration->venue }}
                                            </p>
                                        @endif
                                        @if($celebration->expected_guests)
                                            <p class="mb-2">
                                                <i class="fas fa-users me-2 text-primary"></i>
                                                {{ $celebration->expected_guests }} guests
                                            </p>
                                        @endif
                                        @if($celebration->budget)
                                            <p class="mb-2">
                                                <i class="fas fa-money-bill-wave me-2 text-primary"></i>
                                                TZS {{ number_format($celebration->budget, 2) }}
                                            </p>
                                        @endif
                                        @if($celebration->description)
                                            <p class="mb-3 text-muted small">{{ Str::limit($celebration->description, 100) }}</p>
                                        @endif
                                    </div>
                                    <div class="card-footer bg-transparent">
                                        <div class="btn-group w-100" role="group">
                                            <button class="btn btn-outline-info btn-sm" onclick="viewDetails({{ $celebration->id }})" title="View Details">
                                                <i class="fas fa-eye me-1"></i>View
                                            </button>
                                            <button class="btn btn-outline-primary btn-sm" onclick="openEdit({{ $celebration->id }})" title="Edit Celebration">
                                                <i class="fas fa-edit me-1"></i>Edit
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm" onclick="confirmDelete({{ $celebration->id }})" title="Delete Celebration">
                                                <i class="fas fa-trash me-1"></i>Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12">
                                <div class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-birthday-cake fa-5x mb-4"></i>
                                        <h4>No celebrations found</h4>
                                        <p>Start by adding your first celebration</p>
                                        <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#addCelebrationModal" onclick="openAddCelebration()">
                                            <i class="fas fa-plus me-2"></i>Add First Celebration
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforelse
                        </div>
                    </div>

    @if($celebrations->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $celebrations->withQueryString()->links() }}
    </div>
    @endif
</div>

    <!-- Add Celebration Modal -->
    <div class="modal fade" id="addCelebrationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow-lg service-modal-content" style="border-radius: 20px; overflow: hidden;">
                <!-- Stylish Header -->
                <div class="modal-header border-0 service-modal-header" style="background: linear-gradient(180deg, #17082d 0%, #17082ddd 100%); padding: 1.25rem 1.5rem;">
                    <div class="d-flex align-items-center">
                        <div class="service-icon-wrapper me-3">
                            <i class="fas fa-birthday-cake"></i>
                        </div>
                        <h5 class="modal-title mb-0 fw-bold text-white" id="celebrationModalTitle">
                            Create Celebration
                        </h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <!-- Stylish Body -->
                <div class="modal-body service-modal-body" style="padding: 1.75rem; background: #f8f9fa;">
                    <form id="addCelebrationForm">
                        <input type="hidden" id="editing_celebration_id" value="">
                        <div class="row g-3">
                            <!-- Row 1: Title & Celebrant -->
                            <div class="col-md-6">
                                <label class="form-label service-label mb-2">
                                    <i class="fas fa-star me-1 text-warning"></i>Celebration Title <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control service-input" id="cel_title" placeholder="Enter celebration title" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label service-label mb-2">
                                    <i class="fas fa-user me-1 text-primary"></i>Celebrant Name
                                </label>
                                <input type="text" class="form-control service-input" id="cel_celebrant" placeholder="Enter celebrant name">
                            </div>
                            
                            <!-- Row 2: Type & Venue -->
                            <div class="col-md-6">
                                <label class="form-label service-label mb-2">
                                    <i class="fas fa-tags me-1 text-primary"></i>Celebration Type
                                </label>
                                <select class="form-select service-input" id="cel_type">
                                    <option value="">Select Type</option>
                                    <option value="Birthday">Birthday</option>
                                    <option value="Anniversary">Anniversary</option>
                                    <option value="Wedding">Wedding</option>
                                    <option value="Graduation">Graduation</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label service-label mb-2">
                                    <i class="fas fa-map-marker-alt me-1 text-danger"></i>Venue
                                </label>
                                <input type="text" class="form-control service-input" id="cel_venue" placeholder="Enter venue location">
                            </div>
                            
                            <!-- Row 3: Date & Time -->
                            <div class="col-md-4">
                                <label class="form-label service-label mb-2">
                                    <i class="fas fa-calendar-alt me-1 text-info"></i>Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control service-input" id="cel_date" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label service-label mb-2">
                                    <i class="fas fa-clock me-1 text-success"></i>Start Time
                                </label>
                                <input type="time" class="form-control service-input" id="cel_start">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label service-label mb-2">
                                    <i class="fas fa-clock me-1 text-danger"></i>End Time
                                </label>
                                <input type="time" class="form-control service-input" id="cel_end">
                            </div>
                            
                            <!-- Row 4: Guests & Budget -->
                            <div class="col-md-6">
                                <label class="form-label service-label mb-2">
                                    <i class="fas fa-users me-1 text-info"></i>Expected Guests
                                </label>
                                <input type="number" min="0" class="form-control service-input" id="cel_guests" placeholder="Number of guests">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label service-label mb-2">
                                    <i class="fas fa-money-bill-wave me-1 text-success"></i>Budget (TZS)
                                </label>
                                <input type="number" min="0" step="0.01" class="form-control service-input" id="cel_budget" placeholder="Budget amount">
                            </div>
                            
                            <!-- Row 5: Description -->
                            <div class="col-12">
                                <label class="form-label service-label mb-2">
                                    <i class="fas fa-file-alt me-1 text-primary"></i>Description
                                </label>
                                <textarea class="form-control service-input" id="cel_description" rows="2" placeholder="Enter celebration description"></textarea>
                            </div>
                            
                            <!-- Row 6: Special Requests -->
                            <div class="col-12">
                                <label class="form-label service-label mb-2">
                                    <i class="fas fa-gift me-1 text-warning"></i>Special Requests
                                </label>
                                <textarea class="form-control service-input" id="cel_requests" rows="2" placeholder="Enter special requests"></textarea>
                            </div>
                            
                            <!-- Row 7: Notes -->
                            <div class="col-12">
                                <label class="form-label service-label mb-2">
                                    <i class="fas fa-sticky-note me-1 text-secondary"></i>Notes
                                </label>
                                <textarea class="form-control service-input" id="cel_notes" rows="2" placeholder="Additional notes"></textarea>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary service-btn-cancel" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i>Cancel
                            </button>
                            <button type="submit" class="btn service-btn-save" id="submitButton">
                                <i class="fas fa-save me-1"></i>Save Celebration
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- View Details Modal -->
    <div class="modal fade" id="celebrationDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 18px; overflow: hidden;">
                <div class="modal-header text-white" style="background: linear-gradient(135deg, #1f2b6c 0%, #5b2a86 100%); border: none;">
                    <h5 class="modal-title d-flex align-items-center gap-2"><i class="fas fa-birthday-cake"></i><span>Celebration Details</span></h5>
                    <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light" id="celebrationDetailsBody">
                    <div class="text-center text-muted py-4">Loading...</div>
                </div>
                <div class="modal-footer d-flex justify-content-between align-items-center">
                    <div class="small">
                        <span class="me-1">Powered by</span>
                        <a href="https://emca.tech/#" target="_blank" rel="noopener" class="emca-link fw-semibold">EmCa Technologies</a>
                    </div>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script>
        // View Toggle Functionality
        function switchView(view) {
            const listView = document.getElementById('listView');
            const cardView = document.getElementById('cardView');
            const listBtn = document.getElementById('listViewBtn');
            const cardBtn = document.getElementById('cardViewBtn');

            if (view === 'list') {
                listView.style.display = 'block';
                cardView.style.display = 'none';
                listBtn.classList.add('active');
                cardBtn.classList.remove('active');
                localStorage.setItem('celebrationView', 'list');
            } else {
                listView.style.display = 'none';
                cardView.style.display = 'block';
                listBtn.classList.remove('active');
                cardBtn.classList.add('active');
                localStorage.setItem('celebrationView', 'card');
            }
        }

        // Load saved view preference
        document.addEventListener('DOMContentLoaded', function() {
            const savedView = localStorage.getItem('celebrationView') || 'list';
            switchView(savedView);
            
            // Auto-open add modal if coming from dashboard
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('action') === 'add') {
                openAddCelebration();
            }
        });

        // Modal Functions
        function openAddCelebration() {
            document.getElementById('editing_celebration_id').value = '';
            const titleEl = document.getElementById('celebrationModalTitle');
            if (titleEl) titleEl.textContent = 'Create Celebration';
            const submitBtn = document.getElementById('submitButton');
            if (submitBtn) submitBtn.innerHTML = '<i class="fas fa-save me-1"></i>Save Celebration';
            document.getElementById('addCelebrationForm').reset();
        }

        function openEdit(id) {
            fetch(`/celebrations/${id}`, { headers: { 'Accept': 'application/json' } })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('editing_celebration_id').value = id;
                    const titleEl = document.getElementById('celebrationModalTitle');
                    if (titleEl) titleEl.textContent = 'Edit Celebration';
                    const submitBtn = document.getElementById('submitButton');
                    if (submitBtn) submitBtn.innerHTML = '<i class="fas fa-save me-1"></i>Update Celebration';
                    
                    document.getElementById('cel_title').value = data.title || '';
                    document.getElementById('cel_celebrant').value = data.celebrant_name || '';
                    document.getElementById('cel_type').value = data.type || '';
                    document.getElementById('cel_venue').value = data.venue || '';
                    document.getElementById('cel_date').value = data.celebration_date || '';
                    document.getElementById('cel_start').value = data.start_time || '';
                    document.getElementById('cel_end').value = data.end_time || '';
                    document.getElementById('cel_guests').value = data.expected_guests || '';
                    document.getElementById('cel_budget').value = data.budget || '';
                    document.getElementById('cel_description').value = data.description || '';
                    document.getElementById('cel_requests').value = data.special_requests || '';
                    document.getElementById('cel_notes').value = data.notes || '';
                    
                    new bootstrap.Modal(document.getElementById('addCelebrationModal')).show();
                })
                .catch(() => {
                    Swal.fire('Error', 'Failed to load celebration details', 'error');
                });
        }

        function viewDetails(id) {
            fetch(`/celebrations/${id}`)
                .then(res => res.json())
                .then(data => {
                    // Time formatting function
                    const formatTime = (timeStr) => {
                        if (!timeStr || timeStr === 'TBD') return 'TBD';
                        try {
                            // Handle ISO format
                            if (timeStr.includes('T')) {
                                const time = new Date(timeStr);
                                return time.toLocaleTimeString('en-US', {
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    hour12: true
                                });
                            }
                            // Handle HH:MM:SS format
                            if (timeStr.includes(':')) {
                                const [hours, minutes] = timeStr.split(':');
                                const time = new Date();
                                time.setHours(parseInt(hours), parseInt(minutes), 0);
                                return time.toLocaleTimeString('en-US', {
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    hour12: true
                                });
                            }
                            return timeStr;
                        } catch (e) {
                            return 'TBD';
                        }
                    };

                    const body = document.getElementById('celebrationDetailsBody');
                    body.innerHTML = `
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3"><i class="fas fa-info-circle me-2"></i>Basic Information</h6>
                                <p><strong>Title:</strong> ${data.title || '—'}</p>
                                <p><strong>Celebrant:</strong> ${data.celebrant_name || '—'}</p>
                                <p><strong>Type:</strong> ${data.type ? `<span class="celebration-type-badge">${data.type}</span>` : '—'}</p>
                                <p><strong>Venue:</strong> ${data.venue || '—'}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3"><i class="fas fa-clock me-2"></i>Date & Time</h6>
                                <p><strong>Date:</strong> ${data.celebration_date ? new Date(data.celebration_date).toLocaleDateString() : '—'}</p>
                                <p><strong>Time:</strong> ${data.start_time && data.end_time ? `${formatTime(data.start_time)} - ${formatTime(data.end_time)}` : data.start_time ? formatTime(data.start_time) : '—'}</p>
                                <p><strong>Expected Guests:</strong> ${data.expected_guests || '—'}</p>
                                <p><strong>Budget:</strong> ${data.budget ? `TZS ${parseFloat(data.budget).toLocaleString()}` : '—'}</p>
                            </div>
                        </div>
                        ${data.description ? `<div class="mt-4"><h6 class="text-primary mb-3"><i class="fas fa-file-alt me-2"></i>Description</h6><p>${data.description}</p></div>` : ''}
                        ${data.special_requests ? `<div class="mt-4"><h6 class="text-primary mb-3"><i class="fas fa-gift me-2"></i>Special Requests</h6><p>${data.special_requests}</p></div>` : ''}
                        ${data.notes ? `<div class="mt-4"><h6 class="text-primary mb-3"><i class="fas fa-sticky-note me-2"></i>Notes</h6><p>${data.notes}</p></div>` : ''}
                    `;
                    new bootstrap.Modal(document.getElementById('celebrationDetailsModal')).show();
                })
                .catch(err => {
                    Swal.fire('Error', 'Failed to load celebration details', 'error');
                });
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/celebrations/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Deleted!', data.message, 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Error', data.message || 'Failed to delete celebration', 'error');
                        }
                    })
                    .catch(err => {
                        Swal.fire('Error', 'Failed to delete celebration', 'error');
                    });
                }
            });
        }

        // Form Submission
        document.getElementById('addCelebrationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData();
            const editingId = document.getElementById('editing_celebration_id').value;
            
            formData.append('title', document.getElementById('cel_title').value);
            formData.append('celebrant_name', document.getElementById('cel_celebrant').value);
            formData.append('type', document.getElementById('cel_type').value);
            formData.append('venue', document.getElementById('cel_venue').value);
            formData.append('celebration_date', document.getElementById('cel_date').value);
            const startVal = document.getElementById('cel_start').value;
            const endVal = document.getElementById('cel_end').value;
            if (startVal) formData.append('start_time', startVal);
            if (endVal) formData.append('end_time', endVal);
            formData.append('expected_guests', document.getElementById('cel_guests').value);
            formData.append('budget', document.getElementById('cel_budget').value);
            formData.append('description', document.getElementById('cel_description').value);
            formData.append('special_requests', document.getElementById('cel_requests').value);
            formData.append('notes', document.getElementById('cel_notes').value);
            formData.append('is_public', '1');

            const submitBtn = document.getElementById('submitButton');
            const originalHtml = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';

            const url = editingId ? `/celebrations/${editingId}` : '/celebrations';
            if (editingId) {
                formData.append('_method', 'PUT');
            }

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(async (res) => {
                const contentType = res.headers.get('content-type') || '';
                if (!res.ok) {
                    let message = `HTTP ${res.status}`;
                    if (contentType.includes('application/json')) {
                        const err = await res.json().catch(() => null);
                        if (err && err.message) message = err.message;
                    } else {
                        const text = await res.text().catch(() => '');
                        if (text) message = text.substring(0, 200);
                    }
                    throw new Error(message);
                }
                if (contentType.includes('application/json')) {
                    return res.json();
                }
                return { success: true, message: 'Saved' };
            })
            .then(data => {
                if (data.success) {
                    document.getElementById('addCelebrationForm').reset();
                    document.getElementById('editing_celebration_id').value = '';
                    const titleEl = document.querySelector('#addCelebrationModal .modal-title');
                    if (titleEl) titleEl.textContent = 'Create Celebration';
                    submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Save Celebration';
                    
                    Swal.fire({
                        icon: 'success',
                        title: editingId ? 'Updated' : 'Saved',
                        text: data.message || 'Celebration saved',
                        timer: 1200,
                        showConfirmButton: false
                    }).then(() => location.reload());
                } else {
                    Swal.fire('Error', data.message || 'Failed to save celebration', 'error');
                }
            })
            .catch((err) => {
                Swal.fire('Error', err?.message || 'Failed to save celebration', 'error');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalHtml;
            });
        });
    </script>

    <style>
        .celebration-card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .celebration-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .celebration-header {
            background: linear-gradient(135deg, #940000 0%, #667eea 50%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0;
        }
        .celebration-type-badge {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .celebration-date {
            background: linear-gradient(135deg, #940000, #667eea);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: 600;
        }
        .view-toggle-btn {
            background: linear-gradient(135deg, #940000 0%, #667eea 100%);
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            transition: all 0.3s ease;
        }
        .view-toggle-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(148, 0, 0, 0.3);
        }
        .view-toggle-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        /* Enhanced Modal Animations */
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Form Control Focus Effects */
        .form-control:focus {
            border-color: #940000 !important;
            box-shadow: 0 0 0 0.2rem rgba(148, 0, 0, 0.25) !important;
            transform: translateY(-2px);
        }

        .form-control:hover {
            border-color: #667eea !important;
            transform: translateY(-1px);
        }

        /* Button Hover Effects */
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        /* Card Hover Effects */
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
        }

        /* Floating Label Animation */
        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            color: #940000;
            font-weight: 600;
        }

        /* Modal Backdrop */
        .modal-backdrop {
            background: rgba(23, 8, 45, 0.4) !important;
        }
        
        .modal-backdrop.show {
            opacity: 1 !important;
        }
        
        /* Service Modal Styling (for Celebration Modal) */
        .service-modal-content {
            animation: modalSlideIn 0.3s ease-out;
        }
        
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .service-modal-header {
            position: relative;
            overflow: hidden;
        }
        
        .service-modal-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
            pointer-events: none;
        }
        
        .service-icon-wrapper {
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .service-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #495057;
            letter-spacing: 0.3px;
        }
        
        .service-input {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.5rem 0.75rem;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            background: white;
        }
        
        .service-input:focus {
            border-color: #17082d;
            box-shadow: 0 0 0 0.2rem rgba(23, 8, 45, 0.15);
            transform: translateY(-1px);
            background: white;
        }
        
        .service-input:hover {
            border-color: #ced4da;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .service-btn-save {
            background: linear-gradient(180deg, #17082d 0%, #17082ddd 100%);
            border: none;
            border-radius: 10px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(23, 8, 45, 0.3);
        }
        
        .service-btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(23, 8, 45, 0.4);
            background: linear-gradient(180deg, #1f0d3d 0%, #1f0d3ddd 100%);
            color: white;
        }
        
        .service-btn-cancel {
            border-radius: 10px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid #dee2e6;
        }
        
        .service-btn-cancel:hover {
            transform: translateY(-2px);
            background: #f8f9fa;
            border-color: #adb5bd;
        }
        
        .service-modal-body {
            max-height: 70vh;
            overflow-y: auto;
        }
        
        .service-modal-body::-webkit-scrollbar {
            width: 6px;
        }
        
        .service-modal-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .service-modal-body::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #17082d 0%, #17082ddd 100%);
            border-radius: 10px;
        }
        
        .service-modal-body::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #1f0d3d 0%, #1f0d3ddd 100%);
        }
        
        /* Form Select Styling */
        .service-input.form-select {
            cursor: pointer;
        }
        
        .service-input.form-select:focus {
            border-color: #17082d;
        }
        
        /* Textarea Styling */
        .service-input[rows] {
            resize: vertical;
            min-height: 60px;
        }
    </style>

@endsection

@section('scripts')
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
<script src="{{ asset('js/scripts.js') }}"></script>
@endsection
