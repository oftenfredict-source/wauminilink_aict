@extends('layouts.index')

@section('content')
<style>
    /* Mobile Responsive Styles */
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
            padding-top: 0.25rem !important;
        }

        .d-flex.justify-content-between {
            flex-direction: column;
            align-items: flex-start !important;
        }

        h1.h3 {
            font-size: 1.25rem !important;
            margin-bottom: 1rem !important;
        }

        .table-responsive {
            overflow-x: auto !important;
        }

        .table {
            font-size: 0.85rem !important;
            min-width: 1000px !important;
        }

        .badge {
            font-size: 0.75rem !important;
        }
    }

    .otp-code {
        font-family: 'Courier New', monospace;
        font-weight: bold;
        font-size: 1.1rem;
        letter-spacing: 0.2em;
        color: #495057;
    }

    .status-badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }

    /* Bootstrap Pagination Styling */
    .pagination {
        margin: 0;
        padding: 0;
    }

    .pagination .page-item {
        margin: 0 0.125rem;
    }

    .pagination .page-link {
        border-radius: 0.375rem;
        border: 1px solid #dee2e6;
        color: #495057;
        padding: 0.375rem 0.75rem;
        transition: all 0.2s ease;
    }

    .pagination .page-link:hover {
        background-color: #e9ecef;
        border-color: #dee2e6;
        color: #495057;
    }

    .pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: #fff;
        z-index: 1;
    }

    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
        background-color: #fff;
        border-color: #dee2e6;
        opacity: 0.6;
    }

    .card-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
        padding: 0.75rem 1.25rem;
    }
</style>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-key me-2"></i>OTP Management
        </h1>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-2 col-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total OTPs</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-key fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['active']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6 mb-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Used</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['used']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6 mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Unused</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['unused']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6 mb-3">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Expired</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['expired']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6 mb-3">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Today</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['today']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-header d-md-none" onclick="toggleFilters()" style="cursor: pointer;">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-filter me-2"></i>Filters
                <i class="fas fa-chevron-down float-end" id="filterIcon"></i>
            </h6>
        </div>
        <div class="card-body" id="otpFilters" style="display: block;">
            <form method="GET" action="{{ route('admin.otps') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="used" {{ request('status') == 'used' ? 'selected' : '' }}>Used</option>
                            <option value="unused" {{ request('status') == 'unused' ? 'selected' : '' }}>Unused</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="user_id" class="form-label">User</label>
                        <select name="user_id" id="user_id" class="form-select">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="date_from" class="form-label">Date From</label>
                        <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="date_to" class="form-label">Date To</label>
                        <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" name="search" id="search" class="form-control" placeholder="Email/OTP Code" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i>Apply Filters
                        </button>
                        <a href="{{ route('admin.otps') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- OTPs Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Generated OTPs</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="otpsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>OTP Code</th>
                            <th>User</th>
                            <th>Email/Member ID</th>
                            <th>Status</th>
                            <th>Attempts</th>
                            <th>Created At</th>
                            <th>Expires At</th>
                            <th>Used At</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($otps as $otp)
                            <tr>
                                <td>{{ $otp->id }}</td>
                                <td>
                                    <span class="otp-code">{{ $otp->otp_code }}</span>
                                </td>
                                <td>
                                    @if($otp->user)
                                        <div>{{ $otp->user->name }}</div>
                                        <small class="text-muted">{{ $otp->user->email }}</small>
                                        <br><small class="badge bg-secondary">{{ ucfirst($otp->user->role) }}</small>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>{{ $otp->email }}</td>
                                <td>
                                    @if($otp->is_used)
                                        <span class="badge bg-success status-badge">Used</span>
                                    @elseif($otp->expires_at->isPast())
                                        <span class="badge bg-danger status-badge">Expired</span>
                                    @else
                                        <span class="badge bg-info status-badge">Active</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $otp->attempts >= 5 ? 'bg-danger' : ($otp->attempts > 0 ? 'bg-warning' : 'bg-secondary') }}">
                                        {{ $otp->attempts }}/5
                                    </span>
                                </td>
                                <td>
                                    <div>{{ $otp->created_at->format('Y-m-d H:i:s') }}</div>
                                    <small class="text-muted">{{ $otp->created_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <div>{{ $otp->expires_at->format('Y-m-d H:i:s') }}</div>
                                    <small class="text-muted">{{ $otp->expires_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    @if($otp->used_at)
                                        <div>{{ $otp->used_at->format('Y-m-d H:i:s') }}</div>
                                        <small class="text-muted">{{ $otp->used_at->diffForHumans() }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $otp->ip_address ?? 'N/A' }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                                    <p class="text-muted">No OTPs found matching your criteria.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($otps->hasPages())
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="text-muted small">
                        Showing {{ $otps->firstItem() }} to {{ $otps->lastItem() }} of {{ $otps->total() }} entries
                    </div>
                    <nav aria-label="OTPs pagination">
                        <ul class="pagination mb-0">
                            {{-- Previous Page Link --}}
                            @if ($otps->onFirstPage())
                                <li class="page-item disabled" aria-disabled="true" aria-label="Previous">
                                    <span class="page-link" aria-hidden="true">
                                        <i class="fas fa-chevron-left"></i> Previous
                                    </span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $otps->appends(request()->except('page'))->previousPageUrl() }}" rel="prev" aria-label="Previous">
                                        <i class="fas fa-chevron-left"></i> Previous
                                    </a>
                                </li>
                            @endif

                            {{-- Pagination Elements with Smart Range --}}
                            @php
                                $currentPage = $otps->currentPage();
                                $lastPage = $otps->lastPage();
                                $startPage = max(1, $currentPage - 2);
                                $endPage = min($lastPage, $currentPage + 2);
                                
                                // Show first page if not in range
                                if ($startPage > 1) {
                                    $endPage = min($lastPage, $startPage + 4);
                                }
                                
                                // Show last page if not in range
                                if ($endPage < $lastPage) {
                                    $startPage = max(1, $endPage - 4);
                                }
                            @endphp

                            {{-- First page --}}
                            @if ($startPage > 1)
                                <li class="page-item">
                                    <a class="page-link" href="{{ $otps->appends(request()->except('page'))->url(1) }}">1</a>
                                </li>
                                @if ($startPage > 2)
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                @endif
                            @endif

                            {{-- Page numbers around current page --}}
                            @for ($page = $startPage; $page <= $endPage; $page++)
                                @if ($page == $currentPage)
                                    <li class="page-item active" aria-current="page">
                                        <span class="page-link">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $otps->appends(request()->except('page'))->url($page) }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endfor

                            {{-- Last page --}}
                            @if ($endPage < $lastPage)
                                @if ($endPage < $lastPage - 1)
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                @endif
                                <li class="page-item">
                                    <a class="page-link" href="{{ $otps->appends(request()->except('page'))->url($lastPage) }}">{{ $lastPage }}</a>
                                </li>
                            @endif

                            {{-- Next Page Link --}}
                            @if ($otps->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $otps->appends(request()->except('page'))->nextPageUrl() }}" rel="next" aria-label="Next">
                                        Next <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            @else
                                <li class="page-item disabled" aria-disabled="true" aria-label="Next">
                                    <span class="page-link" aria-hidden="true">
                                        Next <i class="fas fa-chevron-right"></i>
                                    </span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    function toggleFilters() {
        const filters = document.getElementById('otpFilters');
        const icon = document.getElementById('filterIcon');
        
        if (filters.style.display === 'none') {
            filters.style.display = 'block';
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
        } else {
            filters.style.display = 'none';
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
        }
    }

    // Auto-hide filters on mobile after form submission
    @if(request()->hasAny(['status', 'user_id', 'date_from', 'date_to', 'search']))
        @if(request()->isMobile())
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('otpFilters').style.display = 'none';
                document.getElementById('filterIcon').classList.remove('fa-chevron-up');
                document.getElementById('filterIcon').classList.add('fa-chevron-down');
            });
        @endif
    @endif
</script>
@endsection

