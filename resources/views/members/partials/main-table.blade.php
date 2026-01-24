<!-- resources/views/members/partials/main-table.blade.php -->
<!-- This partial contains the filters and members table, as in the original view -->

<!-- Filters & Search - Collapsible on Mobile -->
<form method="GET" action="{{ route('members.index') }}" class="card mb-3 border-0 shadow-sm" id="filtersForm">
    <!-- Filter Header -->
    <div class="card-header bg-white border-bottom p-2 px-3 filter-header" onclick="toggleFilters()">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-filter text-primary"></i>
                <span class="fw-semibold">Filters</span>
                @if(request('search') || request('gender') || request('region') || request('district') || request('ward'))
                    <span class="badge bg-primary rounded-pill" id="activeFiltersCount">{{ (request('search') ? 1 : 0) + (request('gender') ? 1 : 0) + (request('region') ? 1 : 0) + (request('district') ? 1 : 0) + (request('ward') ? 1 : 0) }}</span>
                @endif
            </div>
            <i class="fas fa-chevron-down text-muted d-md-none" id="filterToggleIcon"></i>
        </div>
    </div>
    
    <!-- Filter Body - Collapsible on Mobile -->
    <div class="card-body p-3" id="filterBody">
        <!-- Search - Always visible and compact -->
        <div class="mb-3">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light"><i class="fas fa-search text-muted"></i></span>
                <input type="text" name="search" id="searchInput" value="{{ request('search') }}" class="form-control" placeholder="Search name, phone, email, member ID">
            </div>
        </div>
        
        <!-- Advanced Filters - Compact Grid -->
        <div class="row g-2 mb-3" id="advancedFilters">
            <div class="col-6 col-md-3">
                <label class="form-label small text-muted mb-1">Gender</label>
                <select name="gender" id="genderFilter" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="male" {{ request('gender')==='male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ request('gender')==='female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small text-muted mb-1">Region</label>
                <select name="region" id="regionFilter" class="form-select form-select-sm">
                    <option value="">All</option>
                    @foreach(($regions ?? []) as $region)
                        <option value="{{ $region }}" {{ request('region')===$region ? 'selected' : '' }}>{{ $region }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small text-muted mb-1">District</label>
                <select name="district" id="districtFilter" class="form-select form-select-sm">
                    <option value="">All</option>
                    @foreach(($districts ?? []) as $district)
                        <option value="{{ $district }}" {{ request('district')===$district ? 'selected' : '' }}>{{ $district }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small text-muted mb-1">Ward</label>
                <select name="ward" id="wardFilter" class="form-select form-select-sm">
                    <option value="">All</option>
                    @foreach(($wards ?? []) as $ward)
                        <option value="{{ $ward }}" {{ request('ward')===$ward ? 'selected' : '' }}>{{ $ward }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <!-- Action Buttons - Compact -->
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm flex-fill">
                <i class="fas fa-filter me-1"></i>Apply
            </button>
            <a href="{{ route('members.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-redo me-1"></i>Reset
            </a>
        </div>
    </div>
</form>

<script>
function toggleFilters() {
    // Only toggle on mobile devices
    if (window.innerWidth > 768) {
        return; // Don't toggle on desktop
    }
    
    const filterBody = document.getElementById('filterBody');
    const filterIcon = document.getElementById('filterToggleIcon');
    
    if (!filterBody || !filterIcon) return;
    
    // Check computed style to see if it's visible
    const computedStyle = window.getComputedStyle(filterBody);
    const isVisible = computedStyle.display !== 'none';
    
    if (isVisible) {
        filterBody.style.display = 'none';
        filterIcon.classList.remove('fa-chevron-up');
        filterIcon.classList.add('fa-chevron-down');
    } else {
        filterBody.style.display = 'block';
        filterIcon.classList.remove('fa-chevron-down');
        filterIcon.classList.add('fa-chevron-up');
    }
}

// Handle window resize
window.addEventListener('resize', function() {
    const filterBody = document.getElementById('filterBody');
    const filterIcon = document.getElementById('filterToggleIcon');
    
    if (!filterBody || !filterIcon) return;
    
    if (window.innerWidth > 768) {
        // Always show on desktop
        filterBody.style.display = 'block';
        filterIcon.style.display = 'none';
    } else {
        // On mobile, show chevron
        filterIcon.style.display = 'block';
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const filterBody = document.getElementById('filterBody');
    const filterIcon = document.getElementById('filterToggleIcon');
    
    if (!filterBody || !filterIcon) return;
    
    if (window.innerWidth <= 768) {
        // Mobile: start collapsed
        filterBody.style.display = 'none';
        filterIcon.classList.remove('fa-chevron-up');
        filterIcon.classList.add('fa-chevron-down');
    } else {
        // Desktop: always show
        filterBody.style.display = 'block';
        filterIcon.style.display = 'none';
    }
    
    // Show filters if any are active
    @if(request('search') || request('gender') || request('region') || request('district') || request('ward'))
        if (window.innerWidth <= 768) {
            toggleFilters(); // Expand if filters are active
        }
    @endif
});
</script>

<!-- Members Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover interactive-table align-middle mb-0 @if(!empty($isArchived)) archived-table @endif" id="membersTable">
                <thead class="table-light">
                    <tr>
                        <th class="text-nowrap">#</th>
                        <th>Full Name</th>
                        <th>Member ID</th>
                        <th>Phone</th>
                        @if(!empty($isArchived))
                            <th>Gender</th>
                            <th>Reason</th>
                        @else
                            <th>Email</th>
                            <th>Gender</th>
                        @endif
                        <th class="text-end" style="width: 80px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($members ?? collect()) as $member)
                        @if(!empty($isArchived))
                            @php $snap = $member->member_snapshot ?? []; @endphp
                        @endif
                        <tr id="row-{{ !empty($isArchived) ? $member->member_id : $member->id }}"
                            @if(!empty($isArchived)) style="background-color: #f4f4f4; color: #000;" @endif
                            data-name="{{ strtolower(!empty($isArchived) ? ($snap['full_name'] ?? '') : $member->full_name) }}"
                            data-memberid="{{ strtolower(!empty($isArchived) ? ($snap['member_id'] ?? '') : $member->member_id) }}"
                            data-phone="{{ strtolower(!empty($isArchived) ? ($snap['phone_number'] ?? '') : $member->phone_number) }}"
                            data-email="{{ strtolower(!empty($isArchived) ? ($snap['email'] ?? '') : $member->email) }}"
                            data-gender="{{ strtolower(!empty($isArchived) ? ($snap['gender'] ?? '') : ($member->gender ?? '')) }}"
                            data-region="{{ strtolower(!empty($isArchived) ? ($snap['region'] ?? '') : ($member->region ?? '')) }}"
                            data-district="{{ strtolower(!empty($isArchived) ? ($snap['district'] ?? '') : ($member->district ?? '')) }}"
                            data-ward="{{ strtolower(!empty($isArchived) ? ($snap['ward'] ?? '') : ($member->ward ?? '')) }}">
                            <td class="text-muted">
                                @if(method_exists($members, 'firstItem'))
                                    {{ $members->firstItem() + $loop->index }}
                                @else
                                    {{ $loop->iteration }}
                                @endif
                            </td>
                            <td>{{ !empty($isArchived) ? ($snap['full_name'] ?? '-') : $member->full_name }}</td>
                            <td><span class="badge bg-secondary">{{ !empty($isArchived) ? ($snap['member_id'] ?? '-') : $member->member_id }}</span></td>
                            <td>{{ !empty($isArchived) ? ($snap['phone_number'] ?? '-') : $member->phone_number }}</td>
                            @if(!empty($isArchived))
                                <td>{{ ucfirst($snap['gender'] ?? '-') }}</td>
                                <td>{{ $member->reason ?? '-' }}</td>
                            @else
                                <td>{{ $member->email }}</td>
                                <td>{{ ucfirst($member->gender ?? '-') }}</td>
                            @endif
                            <td class="text-end">
                                <div class="d-flex flex-row gap-1 justify-content-end align-items-center" style="flex-wrap: nowrap;">
                                    <button type="button" class="btn btn-sm btn-outline-info" onclick="if(typeof window.viewDetails === 'function') { window.viewDetails({{ !empty($isArchived) ? $member->member_id : $member->id }}); } else { alert('View details function is not available. Please refresh the page.'); }" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if(!empty($isArchived))
                                        <button type="button" class="btn btn-sm btn-outline-success" onclick="if(typeof window.restoreMember === 'function') { window.restoreMember({{ $member->member_id }}); } else { alert('Restore function is not available. Please refresh the page.'); }" title="Restore Member">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="if(typeof window.openEdit === 'function') { window.openEdit({{ $member->id }}); } else { alert('Edit function is not available. Please refresh the page.'); }" title="Edit Member">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @if(auth()->user()->isAdmin())
                                            <button type="button" class="btn btn-sm btn-outline-success" onclick="if(typeof window.resetPassword === 'function') { window.resetPassword({{ $member->id }}); } else { alert('Reset password function is not available. Please refresh the page.'); }" title="Reset Password">
                                                <i class="fas fa-key"></i>
                                            </button>
                                        @endif
                                        <button type="button" class="btn btn-sm btn-outline-warning" onclick="if(typeof window.confirmDelete === 'function') { window.confirmDelete({{ $member->id }}); } else { alert('Archive function is not available. Please refresh the page.'); }" title="Archive Member">
                                            <i class="fas fa-archive"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-4">No members found.</td></tr>
                    @endforelse
                </tbody>
            </table>
            @if(!empty($isArchived))
            <style>
                .archived-table {
                    background: #f4f4f4 !important;
                }
                .archived-table th, .archived-table td {
                    color: #000 !important;
                    background: #f4f4f4 !important;
                }
                .archived-table tr {
                    background: #f4f4f4 !important;
                }
            </style>
            @endif

            <style>
                /* Action Button Colors */
                .btn-outline-info {
                    border-color: #17a2b8 !important;
                    color: #17a2b8 !important;
                }
                .btn-outline-info:hover {
                    background: linear-gradient(90deg, #17a2b8, #138496) !important;
                    border-color: #17a2b8 !important;
                    color: white !important;
                    transform: translateY(-2px);
                    box-shadow: 0 4px 8px rgba(23, 162, 184, 0.3);
                }

                .btn-outline-primary {
                    border-color: #667eea !important;
                    color: #667eea !important;
                }
                .btn-outline-primary:hover {
                    background: linear-gradient(90deg, #667eea, #764ba2) !important;
                    border-color: #667eea !important;
                    color: white !important;
                    transform: translateY(-2px);
                    box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
                }

                .btn-outline-danger {
                    border-color: #dc3545 !important;
                    color: #dc3545 !important;
                }
                .btn-outline-danger:hover {
                    background: linear-gradient(90deg, #dc3545, #c82333) !important;
                    border-color: #dc3545 !important;
                    color: white !important;
                    transform: translateY(-2px);
                    box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
                }

                .btn-outline-warning {
                    border-color: #ffc107 !important;
                    color: #ffc107 !important;
                }
                .btn-outline-warning:hover {
                    background: linear-gradient(90deg, #ffc107, #e0a800) !important;
                    border-color: #ffc107 !important;
                    color: #212529 !important;
                    transform: translateY(-2px);
                    box-shadow: 0 4px 8px rgba(255, 193, 7, 0.3);
                }

                .btn-outline-success {
                    border-color: #28a745 !important;
                    color: #28a745 !important;
                }
                .btn-outline-success:hover {
                    background: linear-gradient(90deg, #28a745, #218838) !important;
                    border-color: #28a745 !important;
                    color: white !important;
                    transform: translateY(-2px);
                    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
                }

                /* Button group styling */
                .btn-group .btn {
                    transition: all 0.3s ease;
                    font-weight: 600;
                }

                .btn-group .btn i {
                    font-size: 0.9rem;
                }
            </style>
        </div>
    </div>
    @if(isset($members) && method_exists($members, 'firstItem'))
        <div class="card-footer d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                Showing {{ $members->firstItem() }} to {{ $members->lastItem() }} of {{ $members->total() }} entries
            </div>
            <div>
                {{ $members->withQueryString()->links() }}
            </div>
        </div>
    @elseif(isset($members) && $members instanceof \Illuminate\Support\Collection)
        <div class="card-footer d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                Showing {{ $members->count() }} of {{ $members->count() }} entries
            </div>
        </div>
    @endif
</div>

<!-- Archive Modal (should be included once per page, not per row) -->
<div class="modal fade" id="archiveMemberModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-archive me-2"></i>Archive Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="archiveMemberForm">
                    <input type="hidden" id="archive_member_id">
                    <div class="mb-3">
                        <label for="archive_reason" class="form-label">Reason for archiving</label>
                        <textarea class="form-control" id="archive_reason" name="reason" rows="3" required></textarea>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">Archive</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
// Archive member logic (robust, attaches only once)
(function() {
    let archiveMemberId = null;
    window.openArchiveModal = function(id) {
        archiveMemberId = id;
        document.getElementById('archive_member_id').value = id;
        document.getElementById('archive_reason').value = '';
        new bootstrap.Modal(document.getElementById('archiveMemberModal')).show();
    };
    // Attach submit handler only once
    const form = document.getElementById('archiveMemberForm');
    if (form && !form._archiveHandlerAttached) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const id = document.getElementById('archive_member_id').value;
            const reason = document.getElementById('archive_reason').value.trim();
            if (!reason) {
                Swal.fire({ icon: 'warning', title: 'Please provide a reason.' });
                return;
            }
            const formData = new FormData();
            formData.append('reason', reason);
            formData.append('_method', 'DELETE');
            fetch(`{{ url('/members') }}/${id}/archive`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(r => {
                if (r.ok) {
                    return r.json();
                } else if (r.status === 403) {
                    return r.json().then(data => {
                        throw new Error(data.message || 'You do not have permission to archive members. Please contact your administrator.');
                    });
                } else {
                    return r.json().then(data => {
                        throw new Error(data.message || `Server error: ${r.status}`);
                    }).catch(() => {
                        throw new Error(`Server error: ${r.status}`);
                    });
                }
            })
            .then(res => {
                if (res.success) {
                    Swal.fire({ icon: 'success', title: 'Member archived', timer: 1200, showConfirmButton: false }).then(()=>location.reload());
                } else {
                    Swal.fire({ icon: 'error', title: 'Archive failed', text: res.message || 'Please try again.' });
                }
            })
            .catch(error => Swal.fire({ icon: 'error', title: 'Archive failed', text: error.message || 'Network error' }));
        });
        form._archiveHandlerAttached = true;
    }
})();

// Member action buttons - robust event delegation
// This handles all action buttons using event delegation for maximum reliability
(function() {
    // Use event delegation on the document to catch all clicks
    // This works even if buttons are added dynamically (e.g., via AJAX)
    document.addEventListener('click', function(e) {
        const target = e.target.closest('.btn-view-member, .btn-edit-member, .btn-reset-password, .btn-archive-member, .btn-restore-member');
        if (!target) return;
        
        e.preventDefault();
        e.stopPropagation();
        
        const memberId = target.getAttribute('data-member-id');
        if (!memberId) {
            console.error('No member ID found on button');
            return;
        }
        
        const id = parseInt(memberId);
        
        // Handle view member
        if (target.classList.contains('btn-view-member')) {
            console.log('View member clicked, ID:', id);
            console.log('Checking if viewDetails function exists...');
            console.log('typeof window.viewDetails:', typeof window.viewDetails);
            
            // Retry mechanism - wait for function to be available
            function tryViewDetails(attempts = 0) {
                console.log(`Attempt ${attempts + 1}: Checking for viewDetails function...`);
                
                if (typeof window.viewDetails === 'function') {
                    const funcStr = window.viewDetails.toString();
                    console.log('viewDetails function found! Length:', funcStr.length);
                    console.log('Contains fetch:', funcStr.includes('fetch'));
                    
                    if (funcStr.includes('fetch') || funcStr.length > 200) {
                        console.log('✓ Full viewDetails function found, calling with ID:', id);
                        try {
                            window.viewDetails(id);
                            return; // Success, exit
                        } catch (error) {
                            console.error('Error calling viewDetails:', error);
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Failed to view member: ' + (error.message || 'Unknown error'),
                                    confirmButtonText: 'OK'
                                });
                            }
                            return; // Exit on error
                        }
                    } else {
                        console.warn('viewDetails exists but appears to be placeholder, length:', funcStr.length);
                    }
                } else {
                    console.warn('viewDetails function NOT FOUND (typeof:', typeof window.viewDetails, ')');
                }
                
                if (attempts < 10) {
                    // Retry after a short delay (function might still be loading)
                    console.log('Retrying in 100ms... (attempt ' + (attempts + 1) + '/10)');
                    setTimeout(() => tryViewDetails(attempts + 1), 100);
                } else {
                    // After 10 attempts, give up and show "not found" message
                    console.error('❌ viewDetails function NOT FOUND after 10 attempts');
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Function Not Found',
                            html: `
                                <p><strong>The view function is not available.</strong></p>
                                <p>This may be due to:</p>
                                <ul style="text-align: left; display: inline-block;">
                                    <li>JavaScript not fully loaded</li>
                                    <li>Script error preventing function definition</li>
                                    <li>Browser compatibility issue</li>
                                </ul>
                                <p class="mt-3">Please try refreshing the page.</p>
                            `,
                            confirmButtonText: 'Refresh Page',
                            allowOutsideClick: false,
                            showCancelButton: true,
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        });
                    } else {
                        alert('View function is not found. Please refresh the page.');
                        if (confirm('Would you like to refresh the page now?')) {
                            window.location.reload();
                        }
                    }
                }
            }
            
            tryViewDetails();
        }
        // Handle edit member
        else if (target.classList.contains('btn-edit-member')) {
            console.log('Edit member clicked, ID:', id);
            
            // Retry mechanism - wait for function to be available
            function tryOpenEdit(attempts = 0) {
                if (typeof window.openEdit === 'function') {
                    console.log('openEdit function found, calling with ID:', id);
                    try {
                        window.openEdit(id);
                    } catch (error) {
                        console.error('Error calling openEdit:', error);
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to open edit form: ' + (error.message || 'Unknown error'),
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                } else if (attempts < 5) {
                    // Retry after a short delay (function might still be loading)
                    console.log('openEdit not available yet, retrying... (attempt ' + (attempts + 1) + ')');
                    setTimeout(() => tryOpenEdit(attempts + 1), 100);
                } else {
                    // After 5 attempts, give up and show error
                    console.error('openEdit function still not available after 5 attempts');
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Function Not Available',
                            text: 'The edit function failed to load. Please refresh the page.',
                            confirmButtonText: 'Refresh Page',
                            allowOutsideClick: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        alert('Edit function is not available. Please refresh the page.');
                        window.location.reload();
                    }
                }
            }
            
            tryOpenEdit();
        }
        // Handle reset password
        else if (target.classList.contains('btn-reset-password')) {
            console.log('Reset password clicked, ID:', id);
            if (typeof window.resetPassword === 'function') {
                window.resetPassword(id);
            } else {
                console.error('resetPassword function not available');
            }
        }
        // Handle archive member
        else if (target.classList.contains('btn-archive-member')) {
            console.log('Archive member clicked, ID:', id);
            if (typeof window.confirmDelete === 'function') {
                window.confirmDelete(id);
            } else {
                console.error('confirmDelete function not available');
            }
        }
        // Handle restore member
        else if (target.classList.contains('btn-restore-member')) {
            console.log('Restore member clicked, ID:', id);
            if (typeof window.restoreMember === 'function') {
                window.restoreMember(id);
            } else {
                console.error('restoreMember function not available');
            }
        }
    });
    
    console.log('Member action buttons event listeners attached (event delegation)');
    
    // Verification function - can be called from console to check function status
    window.verifyViewDetails = function() {
        console.log('=== VERIFYING viewDetails FUNCTION ===');
        console.log('typeof window.viewDetails:', typeof window.viewDetails);
        if (typeof window.viewDetails === 'function') {
            const funcStr = window.viewDetails.toString();
            console.log('✓ Function exists');
            console.log('Function length:', funcStr.length, 'characters');
            console.log('Contains "fetch":', funcStr.includes('fetch'));
            console.log('Contains "viewDetails":', funcStr.includes('viewDetails'));
            console.log('First 200 chars:', funcStr.substring(0, 200));
            return true;
        } else {
            console.error('❌ Function NOT FOUND');
            console.log('Available window properties:', Object.keys(window).filter(k => k.includes('view') || k.includes('View')));
            return false;
        }
    };
    console.log('✓ Verification function available: call window.verifyViewDetails() in console');
    
    // Fallback: Ensure viewDetails is available (in case main script hasn't loaded)
    // Only define fallback if function doesn't exist OR if it's just the placeholder
    if (typeof window.viewDetails !== 'function') {
        console.warn('viewDetails not found in main script, defining fallback...');
        window.viewDetails = function(id) {
            console.log('Fallback viewDetails called with ID:', id);
            if (!id) {
                console.error('viewDetails: No ID provided');
                return;
            }
            // Try to fetch member details
            fetch(`{{ url('/members') }}/${id}`, { 
                headers: { 'Accept': 'application/json' } 
            })
            .then(r => {
                if (!r.ok) throw new Error('HTTP ' + r.status);
                return r.json();
            })
            .then(m => {
                console.log('Member details loaded via fallback:', m);
                // Check if the main viewDetails function is now available (might have loaded after page load)
                const mainFunc = window.viewDetails;
                if (typeof mainFunc === 'function') {
                    const funcStr = mainFunc.toString();
                    // If it's the full implementation (not our fallback), use it
                    if (funcStr.length > 1000 && funcStr.includes('memberDetailsModal') && funcStr.includes('Swal.fire')) {
                        console.log('Main viewDetails function found, using it instead of fallback');
                        // Temporarily replace fallback with main function and call it
                        window.viewDetails = mainFunc;
                        mainFunc(id);
                        return;
                    }
                }
                // Show basic member info using fallback
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'info',
                        title: m.full_name || 'Member Details',
                        html: `
                            <p><strong>Member ID:</strong> ${m.member_id || 'N/A'}</p>
                            <p><strong>Email:</strong> ${m.email || 'N/A'}</p>
                            <p><strong>Phone:</strong> ${m.phone_number || 'N/A'}</p>
                            <p><strong>Gender:</strong> ${m.gender || 'N/A'}</p>
                            <p class="text-muted small mt-2">Note: Using fallback view. Full details may not be available.</p>
                        `,
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(err => {
                console.error('Error loading member:', err);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load member details: ' + (err.message || 'Unknown error'),
                        confirmButtonText: 'OK'
                    });
                }
            });
        };
        console.log('✓ Fallback viewDetails function defined');
    }
})();
</script>
