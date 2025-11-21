@extends('layouts.index')

@section('content')
        <style>
            .table.interactive-table tbody tr:hover { background-color: #f8f9ff; }
            .table.interactive-table tbody tr td:first-child { border-left: 4px solid #5b2a86; }
            
            
            /* Custom Searchable Dropdown Styles */
            .searchable-select-container {
                position: relative;
            }
            
            .searchable-input {
                min-height: auto !important;
                padding: 0.375rem 0.75rem !important;
                line-height: 1.5 !important;
            }
            
            .searchable-input:focus ~ label,
            .searchable-input.has-value ~ label {
                display: none;
            }
            
            .searchable-input ~ label {
                display: none;
            }
            
            .searchable-dropdown {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                border: 2px solid #e9ecef;
                border-top: none;
                border-radius: 0 0 10px 10px;
                max-height: 200px;
                overflow-y: auto;
                z-index: 1000;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }
            
            .searchable-dropdown-item {
                padding: 10px 15px;
                cursor: pointer;
                border-bottom: 1px solid #f8f9fa;
                transition: background-color 0.2s;
            }
            
            .searchable-dropdown-item:hover {
                background-color: #f8f9fa;
            }
            
            .searchable-dropdown-item.selected {
                background-color: #940000;
                color: white;
            }
            
            .searchable-dropdown-item:last-child {
                border-bottom: none;
            }
        </style>
                    <div class="container-fluid px-4">
                        <div class="d-flex flex-wrap align-items-center justify-content-between mt-4 mb-3 gap-2">
                            <h2 class="mb-0">Church Services</h2>
                            <div class="d-flex gap-2">
                                <a href="{{ route('attendance.index', ['service_type' => 'sunday_service']) }}" class="btn btn-info"><i class="fas fa-users me-2"></i>Record Attendance</a>
                                <a href="{{ route('attendance.statistics') }}" class="btn btn-outline-info"><i class="fas fa-chart-bar me-2"></i>Statistics</a>
                                <a href="{{ route('services.sunday.export.csv', request()->query()) }}" class="btn btn-outline-success"><i class="fas fa-file-excel me-2"></i>Export CSV</a>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addServiceModal"><i class="fas fa-plus me-2"></i>Add Service</button>
                            </div>
                        </div>

                        <form method="GET" action="{{ route('services.sunday.index') }}" class="card mb-3" id="filtersForm">
                            <div class="card-body">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-4">
                                        <label class="form-label">Search</label>
                                        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search theme, preacher, venue">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">From</label>
                                        <input type="date" name="from" value="{{ request('from') }}" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">To</label>
                                        <input type="date" name="to" value="{{ request('to') }}" class="form-control">
                                    </div>
                                    <div class="col-md-2 d-flex gap-2">
                                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter me-2"></i>Apply</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="card">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="text-nowrap">#</th>
                                                <th>Date</th>
                                                <th>Service Type</th>
                                                <th>Theme</th>
                                                <th>Preacher</th>
                                                <th>Coordinator</th>
                                                <th>Time</th>
                                                <th>Venue</th>
                                                <th>Status</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($services as $service)
                                                <tr id="row-{{ $service->id }}">
                                                    <td class="text-muted">{{ $services->firstItem() + $loop->index }}</td>
                                                    <td><span class="badge bg-secondary">{{ optional($service->service_date)->format('d/m/Y') }}</span></td>
                                                    <td>
                                                        @php
                                                            $serviceTypeLabels = [
                                                                'sunday_service' => 'Sunday Service',
                                                                'prayer_meeting' => 'Prayer Meeting',
                                                                'bible_study' => 'Bible Study',
                                                                'youth_service' => 'Youth Service',
                                                                'children_service' => 'Children Service',
                                                                'women_fellowship' => 'Women Fellowship',
                                                                'men_fellowship' => 'Men Fellowship',
                                                                'evangelism' => 'Evangelism',
                                                                'other' => 'Other'
                                                            ];
                                                        @endphp
                                                        <span class="badge bg-primary">{{ $serviceTypeLabels[$service->service_type] ?? ucfirst(str_replace('_', ' ', $service->service_type)) }}</span>
                                                    </td>
                                                    <td>{{ $service->theme ?? '—' }}</td>
                                                    <td>{{ $service->preacher ?? '—' }}</td>
                                                    <td>{{ $service->coordinator ? $service->coordinator->full_name : '—' }}</td>
                                                    @php
                                                        $fmtTime = function($t){
                                                            if (!$t) return '--:--';
                                                            try {
                                                                if (preg_match('/^\d{2}:\d{2}/', $t)) return substr($t,0,5);
                                                                if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}/', $t)) return substr(substr($t,11),0,5);
                                                                return \Carbon\Carbon::parse($t)->format('H:i');
                                                            } catch (\Throwable $e) { return '--:--'; }
                                                        };
                                                    @endphp
                                                    <td>{{ $fmtTime($service->start_time) }} - {{ $fmtTime($service->end_time) }}</td>
                                                    <td>{{ $service->venue ?? '—' }}</td>
                                                    <td>
                                                        @if($service->status === 'completed')
                                                            <span class="badge bg-success">Completed</span>
                                                        @else
                                                            <span class="badge bg-warning">Scheduled</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="btn-group btn-group-sm" role="group">
                                                            <button class="btn btn-outline-info" onclick="viewService({{ $service->id }})"><i class="fas fa-eye"></i></button>
                                                            <button class="btn btn-outline-primary" onclick="openEditService({{ $service->id }})"><i class="fas fa-edit"></i></button>
                                                            <button class="btn btn-outline-danger" onclick="confirmDeleteService({{ $service->id }})"><i class="fas fa-trash"></i></button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="10" class="text-center py-4">No services found.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between align-items-center">
                                <div class="text-muted small">Showing {{ $services->firstItem() }} to {{ $services->lastItem() }} of {{ $services->total() }} entries</div>
                                <div>{{ $services->withQueryString()->links() }}</div>
                            </div>
                        </div>
                    </div>
                </main>
                <footer class="bg-dark text-light py-4 mt-auto">
                    <div class="container px-4">
                        <div class="row align-items-center">
                            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                                <small>&copy; <span id="year"></span> Waumini Link — Version 1.0</small>
                            </div>
                            <div class="col-md-6 text-center text-md-end">
                                <small>Powered by <a href="https://emca.tech/#" class="text-decoration-none text-info fw-semibold">EmCa Technologies</a></small>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        <!-- Add Service Modal -->
        <div class="modal fade" id="addServiceModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0 shadow-lg service-modal-content" style="border-radius: 20px; overflow: hidden;">
                    <!-- Stylish Header -->
                    <div class="modal-header border-0 service-modal-header" style="background: linear-gradient(180deg, #17082d 0%, #17082ddd 100%); padding: 1.25rem 1.5rem;">
                        <div class="d-flex align-items-center">
                            <div class="service-icon-wrapper me-3">
                                <i class="fas fa-church"></i>
                            </div>
                            <h5 class="modal-title mb-0 fw-bold text-white">
                                Create Church Service
                            </h5>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <!-- Stylish Body -->
                    <div class="modal-body service-modal-body" style="padding: 1.75rem; background: #f8f9fa;">
                        <form id="addServiceForm">
                            <div class="row g-3">
                                <!-- Row 1: Service Type & Theme -->
                                <div class="col-md-6">
                                    <label class="form-label service-label mb-2">
                                        <i class="fas fa-church me-1 text-primary"></i>Service Type <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select service-input" id="svc_service_type" required>
                                        <option value=""></option>
                                        <option value="sunday_service">Sunday Service</option>
                                        <option value="prayer_meeting">Prayer Meeting</option>
                                        <option value="bible_study">Bible Study</option>
                                        <option value="youth_service">Youth Service</option>
                                        <option value="children_service">Children Service</option>
                                        <option value="women_fellowship">Women Fellowship</option>
                                        <option value="men_fellowship">Men Fellowship</option>
                                        <option value="evangelism">Evangelism</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-6" id="svc_other_service_wrapper" style="display: none;">
                                    <label class="form-label service-label mb-2">
                                        <i class="fas fa-edit me-1 text-primary"></i>Specify Type
                                    </label>
                                    <input type="text" class="form-control service-input" id="svc_other_service" placeholder="Enter service type">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label service-label mb-2">
                                        <i class="fas fa-star me-1 text-warning"></i>Theme
                                    </label>
                                    <input type="text" class="form-control service-input" id="svc_theme" placeholder="Service theme">
                                </div>
                                
                                <!-- Row 2: Date & Time -->
                                <div class="col-md-4">
                                    <label class="form-label service-label mb-2">
                                        <i class="fas fa-calendar-alt me-1 text-info"></i>Date <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control service-input" id="svc_date" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label service-label mb-2">
                                        <i class="fas fa-clock me-1 text-success"></i>Start Time
                                    </label>
                                    <input type="time" class="form-control service-input" id="svc_start">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label service-label mb-2">
                                        <i class="fas fa-clock me-1 text-danger"></i>End Time
                                    </label>
                                    <input type="time" class="form-control service-input" id="svc_end">
                                </div>
                                
                                <!-- Row 3: Preacher & Coordinator -->
                                <div class="col-md-6">
                                    <label class="form-label service-label mb-2">
                                        <i class="fas fa-user-tie me-1 text-primary"></i>Preacher
                                    </label>
                                    <div class="searchable-select-container">
                                        <input type="text" class="form-control service-input searchable-input" id="svc_preacher_search" placeholder="Search preacher...">
                                        <select class="form-select" id="svc_preacher_id" style="display: none;">
                                            <option value=""></option>
                                            @php
                                                $pastors = \App\Models\Member::whereHas('leadershipPositions', function($query) {
                                                    $query->whereIn('position', ['pastor', 'assistant_pastor'])
                                                          ->where('is_active', true)
                                                          ->where(function($q) {
                                                              $q->whereNull('end_date')
                                                                 ->orWhere('end_date', '>=', now()->toDateString());
                                                          });
                                                })->orderBy('full_name')->get();
                                            @endphp
                                            @foreach($pastors as $member)
                                                <option value="{{ $member->full_name }}" data-text="{{ $member->full_name }} ({{ $member->member_id }})">{{ $member->full_name }} ({{ $member->member_id }})</option>
                                            @endforeach
                                        </select>
                                        <div class="searchable-dropdown" id="svc_preacher_dropdown" style="display: none;"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label service-label mb-2">
                                        <i class="fas fa-user-cog me-1 text-primary"></i>Coordinator
                                    </label>
                                    <div class="searchable-select-container">
                                        <input type="text" class="form-control service-input searchable-input" id="svc_coordinator_search" placeholder="Search coordinator...">
                                        <select class="form-select" id="svc_coordinator_id" style="display: none;">
                                            <option value=""></option>
                                            @foreach(\App\Models\Member::whereIn('membership_type', ['permanent', 'temporary'])->orderBy('full_name')->get() as $member)
                                                <option value="{{ $member->id }}" data-text="{{ $member->full_name }} ({{ $member->member_id }})">{{ $member->full_name }} ({{ $member->member_id }})</option>
                                            @endforeach
                                        </select>
                                        <div class="searchable-dropdown" id="svc_coordinator_dropdown" style="display: none;"></div>
                                    </div>
                                </div>
                                
                                <!-- Row 4: Church Elder & Venue -->
                                <div class="col-md-6">
                                    <label class="form-label service-label mb-2">
                                        <i class="fas fa-user-shield me-1 text-primary"></i>Church Elder
                                    </label>
                                    <div class="searchable-select-container">
                                        <input type="text" class="form-control service-input searchable-input" id="svc_church_elder_search" placeholder="Search church elder...">
                                        <select class="form-select" id="svc_church_elder_id" style="display: none;">
                                            <option value=""></option>
                                        </select>
                                        <div class="searchable-dropdown" id="svc_church_elder_dropdown" style="display: none;"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label service-label mb-2">
                                        <i class="fas fa-map-marker-alt me-1 text-danger"></i>Venue
                                    </label>
                                    <input type="text" class="form-control service-input" id="svc_venue" placeholder="Venue location">
                                </div>
                                
                                <!-- Row 5: Choir, Attendance & Offerings -->
                                <div class="col-md-3">
                                    <label class="form-label service-label mb-2">
                                        <i class="fas fa-music me-1 text-warning"></i>Choir
                                    </label>
                                    <input type="text" class="form-control service-input" id="svc_choir" placeholder="Choir name">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label service-label mb-2">
                                        <i class="fas fa-users me-1 text-info"></i>Registered Members <small class="text-muted">(Optional)</small>
                                    </label>
                                    <input type="number" min="0" class="form-control service-input" id="svc_attendance" placeholder="Count">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label service-label mb-2">
                                        <i class="fas fa-user-plus me-1 text-primary"></i>Guests <small class="text-muted">(Optional)</small>
                                    </label>
                                    <input type="number" min="0" class="form-control service-input" id="svc_guests" placeholder="Count">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label service-label mb-2">
                                        <i class="fas fa-coins me-1 text-success"></i>Offerings <small class="text-muted">(Optional)</small>
                                    </label>
                                    <input type="number" min="0" step="0.01" class="form-control service-input" id="svc_offerings" placeholder="TZS">
                                </div>
                                
                                <!-- Row 6: Scripture Readings -->
                                <div class="col-12">
                                    <label class="form-label service-label mb-2">
                                        <i class="fas fa-book-open me-1 text-primary"></i>Scripture Readings
                                    </label>
                                    <textarea class="form-control service-input" id="svc_readings" rows="2" placeholder="Enter scripture readings"></textarea>
                                </div>
                                
                                <!-- Row 7: Notes -->
                                <div class="col-12">
                                    <label class="form-label service-label mb-2">
                                        <i class="fas fa-sticky-note me-1 text-secondary"></i>Notes
                                    </label>
                                    <textarea class="form-control service-input" id="svc_notes" rows="2" placeholder="Additional notes"></textarea>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                <button type="button" class="btn btn-outline-secondary service-btn-cancel" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </button>
                                <button type="submit" class="btn service-btn-save">
                                    <i class="fas fa-save me-1"></i>Save Service
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <style>
            /* Service Modal Styling */
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
            
            /* Searchable Dropdown Enhancement */
            .searchable-dropdown {
                border: 2px solid #17082d;
                border-top: none;
                border-radius: 0 0 10px 10px;
                box-shadow: 0 4px 12px rgba(23, 8, 45, 0.15);
            }
            
            .searchable-dropdown-item:hover {
                background: linear-gradient(180deg, rgba(23, 8, 45, 0.1) 0%, rgba(23, 8, 45, 0.15) 100%);
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
            
            /* Modal Backdrop */
            .modal-backdrop {
                background: rgba(23, 8, 45, 0.4) !important;
            }
            
            .modal-backdrop.show {
                opacity: 1 !important;
            }
        </style>

        <!-- View Modal -->
        <div class="modal fade" id="serviceDetailsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 18px; overflow: hidden;">
                    <div class="modal-header text-white" style="background: linear-gradient(135deg, #1f2b6c 0%, #5b2a86 100%); border: none;">
                        <h5 class="modal-title d-flex align-items-center gap-2"><i class="fas fa-info-circle"></i><span>Service Details</span></h5>
                        <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body bg-light" id="serviceDetailsBody">
                        <div class="text-center text-muted py-4">Loading...</div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between align-items-center">
                        <div class="small"><span class="me-1">Powered by</span><a href="https://emca.tech/#" target="_blank" rel="noopener" class="emca-link fw-semibold">EmCa Technologies</a></div>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editServiceModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0 shadow-lg service-modal-content" style="border-radius: 20px; overflow: hidden;">
                    <!-- Stylish Header -->
                    <div class="modal-header border-0 service-modal-header" style="background: linear-gradient(180deg, #17082d 0%, #17082ddd 100%); padding: 1.25rem 1.5rem;">
                        <div class="d-flex align-items-center">
                            <div class="service-icon-wrapper me-3">
                                <i class="fas fa-edit"></i>
                            </div>
                            <h5 class="modal-title mb-0 fw-bold text-white">
                                Edit Church Service
                            </h5>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <!-- Stylish Body -->
                    <div class="modal-body service-modal-body" style="padding: 1.75rem; background: #f8f9fa;">
                        <form id="editServiceForm">
                            <input type="hidden" id="edit_id">
                            <div class="row g-3">
                                <!-- Row 1: Service Type & Theme -->
                                <div class="col-md-6">
                                    <label class="form-label service-label mb-2">
                                        <i class="fas fa-church me-1 text-primary"></i>Service Type <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select service-input" id="edit_service_type" required>
                                        <option value=""></option>
                                        <option value="sunday_service">Sunday Service</option>
                                        <option value="prayer_meeting">Prayer Meeting</option>
                                        <option value="bible_study">Bible Study</option>
                                        <option value="youth_service">Youth Service</option>
                                        <option value="children_service">Children Service</option>
                                        <option value="women_fellowship">Women Fellowship</option>
                                        <option value="men_fellowship">Men Fellowship</option>
                                        <option value="evangelism">Evangelism</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-6" id="edit_other_service_wrapper" style="display: none;">
                                    <label class="form-label service-label mb-2">
                                        <i class="fas fa-edit me-1 text-primary"></i>Specify Type
                                    </label>
                                    <input type="text" class="form-control service-input" id="edit_other_service" placeholder="Enter service type">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label service-label mb-2">
                                        <i class="fas fa-star me-1 text-warning"></i>Theme
                                    </label>
                                    <input type="text" class="form-control service-input" id="edit_theme" placeholder="Service theme">
                                </div>
                                
                                <!-- Row 2: Date & Time -->
                                <div class="col-md-4">
                                    <label class="form-label service-label mb-2">
                                        <i class="fas fa-calendar-alt me-1 text-info"></i>Date <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control service-input" id="edit_date" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label service-label mb-2">
                                        <i class="fas fa-clock me-1 text-success"></i>Start Time
                                    </label>
                                    <input type="time" class="form-control service-input" id="edit_start">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label service-label mb-2">
                                        <i class="fas fa-clock me-1 text-danger"></i>End Time
                                    </label>
                                    <input type="time" class="form-control service-input" id="edit_end">
                                </div>
                                
                                <!-- Row 3: Preacher & Coordinator -->
                                <div class="col-md-6">
                                    <label class="form-label service-label mb-2">
                                        <i class="fas fa-user-tie me-1 text-primary"></i>Preacher
                                    </label>
                                    <div class="searchable-select-container">
                                        <input type="text" class="form-control service-input searchable-input" id="edit_preacher_search" placeholder="Search preacher...">
                                        <select class="form-select" id="edit_preacher_id" style="display: none;">
                                            <option value=""></option>
                                            @php
                                                $editPastors = \App\Models\Member::whereHas('leadershipPositions', function($query) {
                                                    $query->whereIn('position', ['pastor', 'assistant_pastor'])
                                                          ->where('is_active', true)
                                                          ->where(function($q) {
                                                              $q->whereNull('end_date')
                                                                 ->orWhere('end_date', '>=', now()->toDateString());
                                                          });
                                                })->orderBy('full_name')->get();
                                            @endphp
                                            @foreach($editPastors as $member)
                                                <option value="{{ $member->full_name }}" data-text="{{ $member->full_name }} ({{ $member->member_id }})">{{ $member->full_name }} ({{ $member->member_id }})</option>
                                            @endforeach
                                        </select>
                                        <div class="searchable-dropdown" id="edit_preacher_dropdown" style="display: none;"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label service-label mb-2">
                                        <i class="fas fa-user-cog me-1 text-primary"></i>Coordinator
                                    </label>
                                    <div class="searchable-select-container">
                                        <input type="text" class="form-control service-input searchable-input" id="edit_coordinator_search" placeholder="Search coordinator...">
                                        <select class="form-select" id="edit_coordinator_id" style="display: none;">
                                            <option value=""></option>
                                            @foreach(\App\Models\Member::whereIn('membership_type', ['permanent', 'temporary'])->orderBy('full_name')->get() as $member)
                                                <option value="{{ $member->id }}" data-text="{{ $member->full_name }} ({{ $member->member_id }})">{{ $member->full_name }} ({{ $member->member_id }})</option>
                                            @endforeach
                                        </select>
                                        <div class="searchable-dropdown" id="edit_coordinator_dropdown" style="display: none;"></div>
                                    </div>
                                </div>
                                
                                <!-- Row 4: Church Elder & Venue -->
                                <div class="col-md-6">
                                    <label class="form-label service-label mb-2">
                                        <i class="fas fa-user-shield me-1 text-primary"></i>Church Elder
                                    </label>
                                    <div class="searchable-select-container">
                                        <input type="text" class="form-control service-input searchable-input" id="edit_church_elder_search" placeholder="Search church elder...">
                                        <select class="form-select" id="edit_church_elder_id" style="display: none;">
                                            <option value=""></option>
                                        </select>
                                        <div class="searchable-dropdown" id="edit_church_elder_dropdown" style="display: none;"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label service-label mb-2">
                                        <i class="fas fa-map-marker-alt me-1 text-danger"></i>Venue
                                    </label>
                                    <input type="text" class="form-control service-input" id="edit_venue" placeholder="Venue location">
                                </div>
                                
                                <!-- Row 5: Choir, Attendance & Offerings -->
                                <div class="col-md-3">
                                    <label class="form-label service-label mb-2">
                                        <i class="fas fa-music me-1 text-warning"></i>Choir
                                    </label>
                                    <input type="text" class="form-control service-input" id="edit_choir" placeholder="Choir name">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label service-label mb-2">
                                        <i class="fas fa-users me-1 text-info"></i>Registered Members <small class="text-muted">(Optional)</small>
                                    </label>
                                    <input type="number" min="0" class="form-control service-input" id="edit_attendance" placeholder="Count">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label service-label mb-2">
                                        <i class="fas fa-user-plus me-1 text-primary"></i>Guests <small class="text-muted">(Optional)</small>
                                    </label>
                                    <input type="number" min="0" class="form-control service-input" id="edit_guests" placeholder="Count">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label service-label mb-2">
                                        <i class="fas fa-coins me-1 text-success"></i>Offerings <small class="text-muted">(Optional)</small>
                                    </label>
                                    <input type="number" min="0" step="0.01" class="form-control service-input" id="edit_offerings" placeholder="TZS">
                                </div>
                                
                                <!-- Row 6: Scripture Readings -->
                                <div class="col-12">
                                    <label class="form-label service-label mb-2">
                                        <i class="fas fa-book-open me-1 text-primary"></i>Scripture Readings
                                    </label>
                                    <textarea class="form-control service-input" id="edit_readings" rows="2" placeholder="Enter scripture readings"></textarea>
                                </div>
                                
                                <!-- Row 7: Notes -->
                                <div class="col-12">
                                    <label class="form-label service-label mb-2">
                                        <i class="fas fa-sticky-note me-1 text-secondary"></i>Notes
                                    </label>
                                    <textarea class="form-control service-input" id="edit_notes" rows="2" placeholder="Additional notes"></textarea>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                <button type="button" class="btn btn-outline-secondary service-btn-cancel" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </button>
                                <button type="submit" class="btn service-btn-save">
                                    <i class="fas fa-save me-1"></i>Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
        <script src="{{ asset('js/scripts.js') }}"></script>
        <script>
            document.getElementById('year').textContent = new Date().getFullYear();
            
            // Handle dynamic "Other" service type input
            function toggleOtherServiceInput(selectId, wrapperId, inputId) {
                const select = document.getElementById(selectId);
                const wrapper = document.getElementById(wrapperId);
                const input = document.getElementById(inputId);
                
                if (select.value === 'other') {
                    wrapper.style.display = 'block';
                    input.required = true;
                } else {
                    wrapper.style.display = 'none';
                    input.required = false;
                    input.value = '';
                }
            }
            
            // Add event listeners for service type dropdowns
            document.getElementById('svc_service_type').addEventListener('change', function() {
                toggleOtherServiceInput('svc_service_type', 'svc_other_service_wrapper', 'svc_other_service');
            });
            
            document.getElementById('edit_service_type').addEventListener('change', function() {
                toggleOtherServiceInput('edit_service_type', 'edit_other_service_wrapper', 'edit_other_service');
            });
            
            // Custom Searchable Dropdown Functionality
            function initializeSearchableDropdowns() {
                // Initialize all searchable inputs
                document.querySelectorAll('.searchable-input').forEach(function(input) {
                    const selectId = input.id.replace('_search', '_id');
                    const dropdownId = input.id.replace('_search', '_dropdown');
                    const select = document.getElementById(selectId);
                    const dropdown = document.getElementById(dropdownId);
                    
                    // Show dropdown on focus
                    input.addEventListener('focus', function() {
                        showDropdown(input, select, dropdown);
                        // Add class to trigger label animation
                        input.classList.add('has-value');
                    });
                    
                    // Hide dropdown when clicking outside
                    document.addEventListener('click', function(e) {
                        if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                            dropdown.style.display = 'none';
                            // Remove has-value class if input is empty
                            if (input.value.length === 0) {
                                input.classList.remove('has-value');
                            }
                        }
                    });
                    
                    // Search functionality
                    input.addEventListener('input', function() {
                        filterOptions(input, select, dropdown);
                        // Add class to trigger label animation when typing
                        if (input.value.length > 0) {
                            input.classList.add('has-value');
                        } else {
                            input.classList.remove('has-value');
                            // Clear the hidden select when input is cleared
                            select.value = '';
                        }
                    });
                    
                    // Handle selection
                    dropdown.addEventListener('click', function(e) {
                        if (e.target.classList.contains('searchable-dropdown-item')) {
                            const option = e.target;
                            const value = option.dataset.value;
                            const text = option.textContent;
                            
                            // For preacher fields, use just the name (value), not the full text with member ID
                            const isPreacherField = input.id.includes('preacher');
                            
                            // Update hidden select
                            select.value = value;
                            
                            // Update input display - for preacher, use just the name; for others, use full text
                            input.value = isPreacherField ? value : text;
                            
                            // Add class to trigger label animation
                            input.classList.add('has-value');
                            
                            // Hide dropdown
                            dropdown.style.display = 'none';
                            
                            // Trigger change event
                            select.dispatchEvent(new Event('change'));
                        }
                    });
                });
            }
            
            function showDropdown(input, select, dropdown) {
                const options = Array.from(select.options).slice(1); // Skip empty option
                dropdown.innerHTML = '';
                
                options.forEach(function(option) {
                    const item = document.createElement('div');
                    item.className = 'searchable-dropdown-item';
                    item.dataset.value = option.value;
                    item.textContent = option.textContent;
                    dropdown.appendChild(item);
                });
                
                dropdown.style.display = 'block';
            }
            
            function filterOptions(input, select, dropdown) {
                const searchTerm = input.value.toLowerCase();
                const options = Array.from(select.options).slice(1); // Skip empty option
                const filteredOptions = options.filter(function(option) {
                    return option.textContent.toLowerCase().includes(searchTerm);
                });
                
                dropdown.innerHTML = '';
                
                const isPreacherField = input.id.includes('preacher');
                
                if (filteredOptions.length === 0) {
                    const noResults = document.createElement('div');
                    noResults.className = 'searchable-dropdown-item';
                    if (isPreacherField && searchTerm.length > 0) {
                        noResults.textContent = 'No pastor found. You can type a custom name.';
                    } else {
                        noResults.textContent = 'No members found';
                    }
                    noResults.style.color = '#6c757d';
                    noResults.style.fontStyle = 'italic';
                    dropdown.appendChild(noResults);
                } else {
                    filteredOptions.forEach(function(option) {
                        const item = document.createElement('div');
                        item.className = 'searchable-dropdown-item';
                        item.dataset.value = option.value;
                        item.textContent = option.textContent;
                        dropdown.appendChild(item);
                    });
                }
                
                dropdown.style.display = 'block';
            }
            
            // Function to load church elders dynamically
            function loadChurchElders(selectId, searchInputId) {
                // Add cache-busting parameter to ensure fresh data
                const url = '{{ route("services.sunday.church.elders") }}?t=' + Date.now();
                console.log('Loading church elders from:', url);
                
                const select = document.getElementById(selectId);
                const searchInput = document.getElementById(searchInputId);
                
                if (!select) {
                    console.error('Select element not found:', selectId);
                    return Promise.resolve(null);
                }
                
                return fetch(url, {
                    headers: { 
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    cache: 'no-cache',
                    credentials: 'same-origin'
                })
                .then(r => {
                    console.log('Response status:', r.status);
                    if (!r.ok) {
                        return r.text().then(text => {
                            console.error('Error response:', text);
                            throw new Error('HTTP ' + r.status + ': ' + text);
                        });
                    }
                    return r.json();
                })
                .then(data => {
                    console.log('Church elders loaded:', data);
                    if (data && data.success && data.church_elders && Array.isArray(data.church_elders)) {
                        // Clear existing options except the first empty one
                        select.innerHTML = '<option value=""></option>';
                        
                        console.log('Adding ' + data.church_elders.length + ' church elders to dropdown');
                        
                        if (data.church_elders.length === 0) {
                            console.warn('No active church elders found');
                        }
                        
                        // Add new church elders
                        data.church_elders.forEach(function(elder) {
                            const option = document.createElement('option');
                            option.value = elder.id;
                            option.setAttribute('data-text', elder.display_text);
                            option.textContent = elder.display_text;
                            select.appendChild(option);
                            console.log('Added elder:', elder.display_text);
                        });
                        
                        // Only clear the search input if not editing (for add modal)
                        if (searchInput && selectId === 'svc_church_elder_id') {
                            searchInput.value = '';
                            searchInput.classList.remove('has-value');
                        }
                        
                        return data;
                    } else {
                        console.warn('Invalid response format:', data);
                        // Keep existing options or show empty
                        if (select.options.length <= 1) {
                            console.warn('No church elders available');
                        }
                        return data;
                    }
                })
                .catch(err => {
                    console.error('Failed to load church elders:', err);
                    // Show error message to user
                    if (searchInput) {
                        searchInput.placeholder = 'Error loading church elders. Please refresh the page.';
                    }
                    return null;
                });
            }

            // Load church elders when Add Service modal is shown
            const addServiceModal = document.getElementById('addServiceModal');
            if (addServiceModal) {
                // Fix aria-hidden accessibility issue
                addServiceModal.addEventListener('show.bs.modal', function() {
                    this.setAttribute('aria-hidden', 'false');
                    console.log('Add Service modal opening, loading church elders...');
                    loadChurchElders('svc_church_elder_id', 'svc_church_elder_search').then(() => {
                        // After loading elders, check if date is already set and auto-populate
                        const serviceDateInput = document.getElementById('svc_date');
                        if (serviceDateInput && serviceDateInput.value) {
                            setTimeout(() => {
                                checkWeeklyAssignmentForDate(serviceDateInput.value);
                            }, 300);
                        }
                    });
                });
                
                // Also check when modal is fully shown
                addServiceModal.addEventListener('shown.bs.modal', function() {
                    this.setAttribute('aria-hidden', 'false');
                    console.log('Add Service modal fully shown');
                    const serviceDateInput = document.getElementById('svc_date');
                    if (serviceDateInput && serviceDateInput.value) {
                        setTimeout(() => {
                            checkWeeklyAssignmentForDate(serviceDateInput.value);
                        }, 500);
                    }
                    // Focus on first input for accessibility
                    const firstInput = this.querySelector('input:not([type="hidden"]), textarea, select');
                    if (firstInput) {
                        setTimeout(() => firstInput.focus(), 100);
                    }
                });
                addServiceModal.addEventListener('hide.bs.modal', function() {
                    this.setAttribute('aria-hidden', 'true');
                });
                addServiceModal.addEventListener('hidden.bs.modal', function() {
                    this.setAttribute('aria-hidden', 'true');
                });
            }

            // Store the service data when opening edit modal (before modal shows)
            let currentEditServiceData = null;
            
            // Load church elders when Edit Service modal is shown
            const editServiceModal = document.getElementById('editServiceModal');
            if (editServiceModal) {
                // Fix aria-hidden accessibility issue
                editServiceModal.addEventListener('show.bs.modal', function() {
                    this.setAttribute('aria-hidden', 'false');
                    console.log('Edit Service modal opening, loading church elders...');
                    loadChurchElders('edit_church_elder_id', 'edit_church_elder_search').then(() => {
                        // After loading church elders, restore the selected value if editing
                        if (currentEditServiceData && currentEditServiceData.church_elder_id) {
                            const select = document.getElementById('edit_church_elder_id');
                            const searchInput = document.getElementById('edit_church_elder_search');
                            if (select && searchInput) {
                                select.value = currentEditServiceData.church_elder_id;
                                
                                const elderOption = select.querySelector(`option[value="${currentEditServiceData.church_elder_id}"]`);
                                if (elderOption) {
                                    searchInput.value = elderOption.textContent;
                                    searchInput.classList.add('has-value');
                                } else if (currentEditServiceData.church_elder) {
                                    // Fallback if option not found
                                    searchInput.value = currentEditServiceData.church_elder.full_name + ' (ID: ' + currentEditServiceData.church_elder_id + ')';
                                    searchInput.classList.add('has-value');
                                }
                            }
                        }
                    });
                });
                editServiceModal.addEventListener('shown.bs.modal', function() {
                    this.setAttribute('aria-hidden', 'false');
                    // Focus on first input for accessibility
                    const firstInput = this.querySelector('input:not([type="hidden"]), textarea, select');
                    if (firstInput) {
                        setTimeout(() => firstInput.focus(), 100);
                    }
                });
                editServiceModal.addEventListener('hide.bs.modal', function() {
                    this.setAttribute('aria-hidden', 'true');
                });
                editServiceModal.addEventListener('hidden.bs.modal', function() {
                    this.setAttribute('aria-hidden', 'true');
                });
            }

            // Function to check weekly assignment and auto-populate church elder
            function checkWeeklyAssignmentForDate(serviceDate) {
                if (!serviceDate) {
                    console.log('No service date provided for weekly assignment check');
                    return;
                }

                const select = document.getElementById('svc_church_elder_id');
                const searchInput = document.getElementById('svc_church_elder_search');
                
                if (!select || !searchInput) {
                    console.warn('Church elder select or search input not found');
                    return;
                }

                // Check if we're in the add service modal (but don't block if modal is opening)
                const addModal = document.getElementById('addServiceModal');
                if (!addModal) {
                    console.warn('Add service modal not found');
                    return;
                }
                
                // Always proceed - don't block based on visibility since modal might be opening
                console.log('Checking weekly assignment - modal state:', {
                    hasShow: addModal.classList.contains('show'),
                    hasFade: addModal.classList.contains('fade'),
                    display: window.getComputedStyle(addModal).display
                });

                console.log('Checking weekly assignment for date:', serviceDate);
                
                // Ensure date is in YYYY-MM-DD format
                let formattedDate = serviceDate;
                if (serviceDate.includes('T')) {
                    formattedDate = serviceDate.split('T')[0];
                }

                fetch(`{{ url('/services/sunday/weekly-assignment') }}?date=${formattedDate}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    cache: 'no-cache',
                    credentials: 'same-origin'
                })
                .then(r => {
                    if (!r.ok) {
                        console.error('Error checking weekly assignment:', r.status);
                        return r.text().then(text => {
                            console.error('Error response:', text);
                            return null;
                        });
                    }
                    return r.json();
                })
                .then(data => {
                    console.log('Weekly assignment API response:', data);
                    
                    if (data && data.success && data.has_assignment && data.assignment) {
                        const assignment = data.assignment;
                        console.log('Found weekly assignment:', assignment);

                        // First, ensure church elders are loaded
                        console.log('Loading church elders before auto-populating...');
                        loadChurchElders('svc_church_elder_id', 'svc_church_elder_search').then(() => {
                            // Wait a bit more to ensure DOM is updated
                            setTimeout(() => {
                                console.log('Church elders loaded, checking for member ID:', assignment.member_id);
                                console.log('Assignment data:', assignment);
                                console.log('Available options in dropdown:', Array.from(select.options).map(opt => ({
                                    value: opt.value, 
                                    text: opt.textContent,
                                    dataText: opt.getAttribute('data-text')
                                })));
                                
                                // Check if the assigned member exists in the dropdown
                                // The member_id from assignment should match the option value (which is the member's id)
                                let option = select.querySelector(`option[value="${assignment.member_id}"]`);
                                
                                // If not found by exact value, try to find by data attribute or text
                                if (!option) {
                                    console.log('Option not found by exact value, trying alternative methods...');
                                    // Try finding by data-text attribute
                                    option = Array.from(select.options).find(opt => {
                                        const dataText = opt.getAttribute('data-text');
                                        const textContent = opt.textContent.trim();
                                        const valueMatch = opt.value == assignment.member_id;
                                        const textMatch = dataText === assignment.display_text || textContent === assignment.display_text;
                                        
                                        console.log('Checking option:', {
                                            value: opt.value,
                                            dataText: dataText,
                                            textContent: textContent,
                                            valueMatch: valueMatch,
                                            textMatch: textMatch
                                        });
                                        
                                        return valueMatch || textMatch;
                                    });
                                }
                                
                                if (option) {
                                    console.log('Found matching option:', {
                                        value: option.value,
                                        text: option.textContent,
                                        dataText: option.getAttribute('data-text')
                                    });
                                }
                                
                                if (option) {
                                    // Member exists in dropdown, select it
                                    select.value = option.value;
                                    searchInput.value = assignment.display_text;
                                    searchInput.classList.add('has-value');
                                    
                                    // Trigger input event to ensure the searchable dropdown updates
                                    const inputEvent = new Event('input', { bubbles: true });
                                    searchInput.dispatchEvent(inputEvent);
                                    
                                    // Also trigger change event
                                    const changeEvent = new Event('change', { bubbles: true });
                                    select.dispatchEvent(changeEvent);
                                    
                                    console.log('✓ Auto-populated church elder:', assignment.display_text, 'with option value:', option.value);
                                } else {
                                    // Member not in dropdown - log for debugging
                                    console.warn('⚠ Assigned member not found in church elders dropdown:', {
                                        member_id: assignment.member_id,
                                        member_name: assignment.member_name,
                                        display_text: assignment.display_text,
                                        is_active_elder: data.is_active_elder,
                                        available_options: Array.from(select.options).map(opt => ({value: opt.value, text: opt.textContent}))
                                    });
                                    
                                    // Still try to set the value in the search input (user can see it even if not in dropdown)
                                    if (data.is_active_elder !== false) {
                                        searchInput.value = assignment.display_text;
                                        searchInput.classList.add('has-value');
                                        
                                        // Try to create a temporary option if possible
                                        const tempOption = document.createElement('option');
                                        tempOption.value = assignment.member_id;
                                        tempOption.textContent = assignment.display_text;
                                        tempOption.setAttribute('data-text', assignment.display_text);
                                        select.appendChild(tempOption);
                                        select.value = assignment.member_id;
                                        
                                        console.log('Created temporary option and set value:', assignment.display_text);
                                    }
                                }
                            }, 200); // Small delay to ensure DOM is updated
                        }).catch(err => {
                            console.error('Error loading church elders:', err);
                            // Even if loading fails, try to set the value
                            if (assignment && assignment.display_text) {
                                searchInput.value = assignment.display_text;
                                searchInput.classList.add('has-value');
                                console.log('Set value despite loading error');
                            }
                        });
                    } else {
                        console.log('No weekly assignment found for date:', serviceDate);
                    }
                })
                .catch(err => {
                    console.error('Failed to check weekly assignment:', err);
                });
            }

            // Initialize when page loads
            document.addEventListener('DOMContentLoaded', function() {
                initializeSearchableDropdowns();
                // Pre-load church elders for add modal on page load
                console.log('Page loaded, pre-loading church elders for add modal...');
                loadChurchElders('svc_church_elder_id', 'svc_church_elder_search');

                // Listen for date changes in the add service form
                const serviceDateInput = document.getElementById('svc_date');
                if (serviceDateInput) {
                    // Listen to both change and input events for better responsiveness
                    serviceDateInput.addEventListener('change', function() {
                        const selectedDate = this.value;
                        console.log('Date changed to:', selectedDate);
                        if (selectedDate) {
                            // Ensure church elders are loaded first, then check assignment
                            loadChurchElders('svc_church_elder_id', 'svc_church_elder_search').then(() => {
                                setTimeout(() => {
                                    checkWeeklyAssignmentForDate(selectedDate);
                                }, 300);
                            });
                        } else {
                            // Clear church elder if date is cleared
                            const select = document.getElementById('svc_church_elder_id');
                            const searchInput = document.getElementById('svc_church_elder_search');
                            if (select && searchInput) {
                                select.value = '';
                                searchInput.value = '';
                                searchInput.classList.remove('has-value');
                            }
                        }
                    });
                    
                    // Also listen to input event for immediate feedback
                    serviceDateInput.addEventListener('input', function() {
                        const selectedDate = this.value;
                        // Only check if date looks complete (YYYY-MM-DD format, 10 chars)
                        if (selectedDate && selectedDate.length === 10) {
                            console.log('Date input detected:', selectedDate);
                            loadChurchElders('svc_church_elder_id', 'svc_church_elder_search').then(() => {
                                setTimeout(() => {
                                    checkWeeklyAssignmentForDate(selectedDate);
                                }, 500);
                            });
                        }
                    });

                }
            });
            function viewService(id){
                fetch(`{{ url('/services/sunday') }}/${id}`, { headers: { 'Accept': 'application/json' } })
                    .then(r => { if (!r.ok) throw new Error('HTTP '+r.status); return r.json(); })
                    .then(s => {
                        const fmtTime = (t) => {
                            if (!t) return '—';
                            try {
                                // Handle ISO or "YYYY-MM-DD HH:MM:SS"
                                if (t.includes('T') || /\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}/.test(t)) {
                                    const d = new Date(t);
                                    return d.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                                }
                                // Handle HH:MM(:SS)
                                if (/^\d{2}:\d{2}/.test(t)) {
                                    const [hh, mm] = t.split(':');
                                    const d = new Date();
                                    d.setHours(parseInt(hh), parseInt(mm), 0);
                                    return d.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                                }
                                return t;
                            } catch { return '—'; }
                        };
                        const fmtDate = (d) => d ? new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : '—';
                        const fmtCurrency = (amount) => (amount || amount === 0) ? `TZS ${parseFloat(amount).toLocaleString()}` : '—';

                        const serviceTypeLabels = {
                            'sunday_service': 'Sunday Service',
                            'prayer_meeting': 'Prayer Meeting',
                            'bible_study': 'Bible Study',
                            'youth_service': 'Youth Service',
                            'children_service': 'Children Service',
                            'women_fellowship': 'Women Fellowship',
                            'men_fellowship': 'Men Fellowship',
                            'evangelism': 'Evangelism',
                            'other': 'Other'
                        };

                        const basicInfo = `
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3"><i class="fas fa-info-circle me-2"></i>Basic Information</h6>
                                <div class="mb-2"><strong>Service Type:</strong> ${serviceTypeLabels[s.service_type] || s.service_type || '—'}</div>
                                <div class="mb-2"><strong>Theme:</strong> ${s.theme ?? '—'}</div>
                                <div class="mb-2"><strong>Preacher:</strong> ${s.preacher ?? '—'}</div>
                                <div class="mb-2"><strong>Coordinator:</strong> ${s.coordinator ? s.coordinator.full_name : '—'}</div>
                                <div class="mb-2"><strong>Church Elder:</strong> ${s.church_elder ? s.church_elder.full_name : '—'}</div>
                                <div class="mb-2"><strong>Venue:</strong> ${s.venue ?? '—'}</div>
                                <div class="mb-2"><strong>Choir:</strong> ${s.choir ?? '—'}</div>
                            </div>`;

                        const dateTime = `
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3"><i class="fas fa-clock me-2"></i>Date & Time</h6>
                                <div class="mb-2"><strong>Date:</strong> ${fmtDate(s.service_date)}</div>
                                <div class="mb-2"><strong>Time:</strong> ${fmtTime(s.start_time)} ${s.end_time ? ' - ' + fmtTime(s.end_time) : ''}</div>
                                <div class="mb-2"><strong>Status:</strong> ${s.status === 'completed' ? '<span class="badge bg-success">Completed</span>' : '<span class="badge bg-warning">Scheduled</span>'}</div>
                                <div class="mb-2"><strong>Registered Members:</strong> ${s.attendance_count ?? '—'}</div>
                                <div class="mb-2"><strong>Guests:</strong> ${s.guests_count ?? '—'}</div>
                                <div class="mb-2"><strong>Total Attendance:</strong> ${(parseInt(s.attendance_count || 0) + parseInt(s.guests_count || 0)) || '—'}</div>
                                <div class="mb-2"><strong>Offerings:</strong> ${fmtCurrency(s.offerings_amount)}</div>
                            </div>`;

                        const scripture = s.scripture_readings ? `
                            <div class="mt-4">
                                <h6 class="text-primary mb-3"><i class="fas fa-bible me-2"></i>Scripture Readings</h6>
                                <div class="p-3 bg-white border rounded">${s.scripture_readings}</div>
                            </div>` : '';

                        const notes = s.notes ? `
                            <div class="mt-4">
                                <h6 class="text-primary mb-3"><i class="fas fa-sticky-note me-2"></i>Notes</h6>
                                <div class="p-3 bg-white border rounded">${s.notes}</div>
                            </div>` : '';

                        const html = `
                            <div class="container-fluid">
                                <div class="row g-3">
                                    ${basicInfo}
                                    ${dateTime}
                                </div>
                                ${scripture}
                                ${notes}
                            </div>`;

                        document.getElementById('serviceDetailsBody').innerHTML = html;
                        new bootstrap.Modal(document.getElementById('serviceDetailsModal')).show();
                    })
                    .catch(() => {
                        document.getElementById('serviceDetailsBody').innerHTML = '<div class="text-danger">Failed to load details.</div>';
                        new bootstrap.Modal(document.getElementById('serviceDetailsModal')).show();
                    });
            }
            function openEditService(id){
                fetch(`{{ url('/services/sunday') }}/${id}`, { headers: { 'Accept': 'application/json' } })
                    .then(r => r.json())
                    .then(s => {
                        // Store service data for use in modal show event
                        currentEditServiceData = s;
                        document.getElementById('edit_id').value = s.id;
                        document.getElementById('edit_date').value = (s.service_date || '');
                        
                        // Handle service type - check if it's a custom "other" type
                        const serviceTypeLabels = ['sunday_service', 'prayer_meeting', 'bible_study', 'youth_service', 'children_service', 'women_fellowship', 'men_fellowship', 'evangelism'];
                        if (serviceTypeLabels.includes(s.service_type)) {
                            document.getElementById('edit_service_type').value = s.service_type || '';
                            document.getElementById('edit_other_service_wrapper').style.display = 'none';
                            document.getElementById('edit_other_service').value = '';
                        } else {
                            document.getElementById('edit_service_type').value = 'other';
                            document.getElementById('edit_other_service_wrapper').style.display = 'block';
                            document.getElementById('edit_other_service').value = s.service_type || '';
                        }
                        
                        document.getElementById('edit_start').value = (s.start_time || '');
                        document.getElementById('edit_end').value = (s.end_time || '');
                        document.getElementById('edit_theme').value = s.theme || '';
                        document.getElementById('edit_preacher_search').value = s.preacher || '';
                        if (s.preacher) {
                            document.getElementById('edit_preacher_search').classList.add('has-value');
                        }
                        // Clear search inputs first
                        document.getElementById('edit_coordinator_search').value = '';
                        document.getElementById('edit_church_elder_search').value = '';
                        document.getElementById('edit_coordinator_search').classList.remove('has-value');
                        document.getElementById('edit_church_elder_search').classList.remove('has-value');
                        
                        // Set hidden select values (church elder will be set when modal shows and options are loaded)
                        document.getElementById('edit_coordinator_id').value = s.coordinator_id || '';
                        document.getElementById('edit_church_elder_id').value = s.church_elder_id || '';
                        
                        // Update coordinator search input with selected value
                        if (s.coordinator_id) {
                            const coordinatorOption = document.querySelector(`#edit_coordinator_id option[value="${s.coordinator_id}"]`);
                            if (coordinatorOption) {
                                document.getElementById('edit_coordinator_search').value = coordinatorOption.textContent;
                                document.getElementById('edit_coordinator_search').classList.add('has-value');
                            } else if (s.coordinator) {
                                // Fallback: use the coordinator name from API if option not found
                                document.getElementById('edit_coordinator_search').value = s.coordinator.full_name + ' (ID: ' + s.coordinator_id + ')';
                                document.getElementById('edit_coordinator_search').classList.add('has-value');
                            }
                        }
                        
                        // Note: Church elder will be set in the modal show event after options are loaded
                        document.getElementById('edit_venue').value = s.venue || '';
                        document.getElementById('edit_attendance').value = s.attendance_count || '';
                        document.getElementById('edit_guests').value = s.guests_count || '';
                        document.getElementById('edit_offerings').value = s.offerings_amount || '';
                        document.getElementById('edit_readings').value = s.scripture_readings || '';
                        document.getElementById('edit_choir').value = s.choir || '';
                        document.getElementById('edit_notes').value = s.notes || '';
                        
                        // Show modal first, then initialize Select2
                        new bootstrap.Modal(document.getElementById('editServiceModal')).show();
                        
                        // Initialize Select2 after modal is shown
                        setTimeout(function() {
                            initializeSelect2();
                        }, 500);
                    });
            }
            document.getElementById('addServiceForm').addEventListener('submit', function(e){
                e.preventDefault();
                const fd = new FormData();
                const serviceDate = document.getElementById('svc_date').value;
                console.log('Service date value:', serviceDate);
                fd.append('service_date', serviceDate);
                const serviceType = document.getElementById('svc_service_type').value;
                const otherService = document.getElementById('svc_other_service').value;
                fd.append('service_type', serviceType === 'other' && otherService ? otherService : serviceType);
                fd.append('start_time', document.getElementById('svc_start').value);
                fd.append('end_time', document.getElementById('svc_end').value);
                fd.append('theme', document.getElementById('svc_theme').value);
                fd.append('preacher', document.getElementById('svc_preacher_search').value);
                fd.append('coordinator_id', document.getElementById('svc_coordinator_id').value);
                fd.append('church_elder_id', document.getElementById('svc_church_elder_id').value);
                fd.append('venue', document.getElementById('svc_venue').value);
                // Handle empty values for optional fields
                const attendanceValue = document.getElementById('svc_attendance').value;
                const guestsValue = document.getElementById('svc_guests').value;
                const offeringsValue = document.getElementById('svc_offerings').value;
                
                if (attendanceValue && attendanceValue.trim() !== '') {
                    fd.append('attendance_count', attendanceValue);
                }
                if (guestsValue && guestsValue.trim() !== '') {
                    fd.append('guests_count', guestsValue);
                }
                if (offeringsValue && offeringsValue.trim() !== '') {
                    fd.append('offerings_amount', offeringsValue);
                }
                fd.append('scripture_readings', document.getElementById('svc_readings').value);
                fd.append('choir', document.getElementById('svc_choir').value);
                fd.append('notes', document.getElementById('svc_notes').value);
                fd.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                console.log('CSRF Token for add:', csrfToken);
                console.log('Form data being sent:', Object.fromEntries(fd));
                
                fetch(`{{ route('services.sunday.store') }}`, { 
                    method: 'POST', 
                    headers: { 
                        'X-Requested-With': 'XMLHttpRequest'
                    }, 
                    body: fd 
                })
                    .then(r => {
                        console.log('Response status:', r.status);
                        if (!r.ok) {
                            return r.text().then(text => {
                                console.log('Error response:', text);
                                try {
                                    const errorData = JSON.parse(text);
                                    console.log('Parsed error data:', errorData);
                                    throw new Error(`HTTP ${r.status}: ${errorData.message || r.statusText}`);
                                } catch (e) {
                                    throw new Error(`HTTP ${r.status}: ${r.statusText} - ${text.substring(0, 200)}`);
                                }
                            });
                        }
                        return r.json();
                    })
                    .then(res => { 
                        console.log('Response data:', res);
                        if(res.success){ 
                            Swal.fire({ icon:'success', title:'Saved', timer:1200, showConfirmButton:false }).then(()=>location.reload()); 
                        } else { 
                            Swal.fire({ icon:'error', title:'Failed', text: res.message || 'Try again' }); 
                        } 
                    })
                    .catch(error => {
                        console.error('Error details:', error);
                        Swal.fire({ 
                            icon:'error', 
                            title:'Error', 
                            text: error.message || 'Network error occurred',
                            showConfirmButton: true
                        });
                    });
            });
            document.getElementById('editServiceForm').addEventListener('submit', function(e){
                e.preventDefault();
                const id = document.getElementById('edit_id').value;
                const fd = new FormData();
                fd.append('service_date', document.getElementById('edit_date').value);
                const editServiceType = document.getElementById('edit_service_type').value;
                const editOtherService = document.getElementById('edit_other_service').value;
                fd.append('service_type', editServiceType === 'other' && editOtherService ? editOtherService : editServiceType);
                fd.append('start_time', document.getElementById('edit_start').value);
                fd.append('end_time', document.getElementById('edit_end').value);
                fd.append('theme', document.getElementById('edit_theme').value);
                fd.append('preacher', document.getElementById('edit_preacher_search').value);
                fd.append('coordinator_id', document.getElementById('edit_coordinator_id').value);
                fd.append('church_elder_id', document.getElementById('edit_church_elder_id').value);
                fd.append('venue', document.getElementById('edit_venue').value);
                // Handle empty values for optional fields
                const editAttendanceValue = document.getElementById('edit_attendance').value;
                const editGuestsValue = document.getElementById('edit_guests').value;
                const editOfferingsValue = document.getElementById('edit_offerings').value;
                
                if (editAttendanceValue && editAttendanceValue.trim() !== '') {
                    fd.append('attendance_count', editAttendanceValue);
                }
                if (editGuestsValue && editGuestsValue.trim() !== '') {
                    fd.append('guests_count', editGuestsValue);
                }
                if (editOfferingsValue && editOfferingsValue.trim() !== '') {
                    fd.append('offerings_amount', editOfferingsValue);
                }
                fd.append('scripture_readings', document.getElementById('edit_readings').value);
                fd.append('choir', document.getElementById('edit_choir').value);
                fd.append('notes', document.getElementById('edit_notes').value);
                fd.append('_method', 'PUT');
                fd.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                console.log('CSRF Token for edit:', csrfToken);
                
                fetch(`{{ url('/services/sunday') }}/${id}`, { 
                    method: 'POST', 
                    headers: { 
                        'X-Requested-With': 'XMLHttpRequest'
                    }, 
                    body: fd 
                })
                    .then(r => {
                        console.log('Edit response status:', r.status);
                        if (!r.ok) {
                            return r.text().then(text => {
                                console.log('Edit error response:', text);
                                try {
                                    const errorData = JSON.parse(text);
                                    console.log('Parsed edit error data:', errorData);
                                    throw new Error(`HTTP ${r.status}: ${errorData.message || r.statusText}`);
                                } catch (e) {
                                    throw new Error(`HTTP ${r.status}: ${r.statusText} - ${text.substring(0, 200)}`);
                                }
                            });
                        }
                        return r.json();
                    })
                    .then(res => { 
                        console.log('Edit response data:', res);
                        if(res.success){ 
                            Swal.fire({ icon:'success', title:'Saved', timer:1200, showConfirmButton:false }).then(()=>location.reload()); 
                        } else { 
                            Swal.fire({ icon:'error', title:'Failed', text: res.message || 'Try again' }); 
                        } 
                    })
                    .catch(error => {
                        console.error('Edit error details:', error);
                        Swal.fire({ 
                            icon:'error', 
                            title:'Error', 
                            text: error.message || 'Network error occurred',
                            showConfirmButton: true
                        });
                    });
            });
            function confirmDeleteService(id){
                Swal.fire({ title:'Delete service?', text:'This action cannot be undone.', icon:'warning', showCancelButton:true, confirmButtonText:'Yes, delete', cancelButtonText:'Cancel', confirmButtonColor:'#dc3545' })
                .then((result)=>{ if(result.isConfirmed){ fetch(`{{ url('/services/sunday') }}/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } })
                    .then(r => r.json())
                    .then(res => { if(res.success){ document.getElementById(`row-${id}`)?.remove(); Swal.fire({ icon:'success', title:'Deleted', timer:1200, showConfirmButton:false }); } else { Swal.fire({ icon:'error', title:'Delete failed', text: res.message || 'Try again' }); } })
                    .catch(()=> Swal.fire({ icon:'error', title:'Error', text:'Request failed.' })); } });
            }

        </script>
@endsection


