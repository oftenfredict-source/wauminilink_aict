@extends('layouts.index')

@section('content')
<style>
    /* Ensure badge text is always visible with proper colors - works with Bootstrap 4 and 5 */
    .badge.badge-danger,
    .badge[class*="badge-danger"] {
        background-color: #dc3545 !important;
        color: white !important;
        font-weight: 600 !important;
        padding: 0.35em 0.65em !important;
        display: inline-block !important;
    }
    
    .badge.badge-success,
    .badge[class*="badge-success"] {
        background-color: #198754 !important;
        color: white !important;
        font-weight: 600 !important;
        padding: 0.35em 0.65em !important;
        display: inline-block !important;
    }
    
    .badge.badge-info,
    .badge[class*="badge-info"] {
        background-color: #0dcaf0 !important;
        color: white !important;
        font-weight: 600 !important;
        padding: 0.35em 0.65em !important;
        display: inline-block !important;
    }
    
    .badge.badge-secondary,
    .badge[class*="badge-secondary"] {
        background-color: #6c757d !important;
        color: white !important;
        font-weight: 600 !important;
        padding: 0.35em 0.65em !important;
        display: inline-block !important;
    }
    
    .badge.badge-warning,
    .badge[class*="badge-warning"] {
        background-color: #ffc107 !important;
        color: #212529 !important;
        font-weight: 600 !important;
        padding: 0.35em 0.65em !important;
        display: inline-block !important;
    }
    
    /* Fallback for any badge */
    .badge {
        display: inline-block !important;
        padding: 0.35em 0.65em !important;
        font-weight: 600 !important;
        border-radius: 0.25rem !important;
    }
</style>

<div class="container-fluid px-4">
    <div class="row mb-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm dashboard-header" style="background:#17082d;">
                <div class="card-body text-white py-2 px-3">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center border border-white border-2" style="width:48px; height:48px; background:rgba(255,255,255,.15);">
                                <i class="fas fa-user-check text-white"></i>
                            </div>
                            <div class="lh-sm">
                                <h5 class="mb-0 fw-semibold" style="color: white !important;">User Sessions</h5>
                                <small style="color: white !important;">Monitor and manage active sessions</small>
                            </div>
                        </div>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.sessions') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">User</label>
                    <select name="user_id" class="form-control">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="active_only" class="form-control">
                        <option value="">All Sessions</option>
                        <option value="1" {{ request('active_only') == '1' ? 'selected' : '' }}>Active Only (Last 24h)</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label><br>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Apply Filters
                    </button>
                    <a href="{{ route('admin.sessions') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Sessions Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Sessions ({{ $sessions->count() }} total)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>IP Address</th>
                            <th>User Agent</th>
                            <th>Last Activity</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sessions as $session)
                        <tr class="{{ $session->is_current ? 'table-info' : '' }}">
                            <td>
                                <strong>{{ $session->name }}</strong><br>
                                <small class="text-muted">{{ $session->email }}</small>
                                @if(isset($session->is_login_blocked) && $session->is_login_blocked)
                                <br><span class="badge badge-danger" style="font-size: 0.7em; margin-top: 2px;">
                                    <i class="fas fa-ban"></i> Login Blocked ({{ $session->remaining_block_time }} min)
                                </span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $roleBadgeClass = match(strtolower($session->role)) {
                                        'admin' => 'badge-danger',
                                        'pastor' => 'badge-warning',
                                        'secretary' => 'badge-info',
                                        'treasurer' => 'badge-secondary',
                                        default => 'badge-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $roleBadgeClass }}">
                                    {{ ucfirst($session->role) }}
                                </span>
                            </td>
                            <td><small>{{ $session->ip_address }}</small></td>
                            <td>
                                <small title="{{ $session->user_agent }}">
                                    {{ Str::limit($session->user_agent, 50) }}
                                </small>
                            </td>
                            <td>
                                <small>{{ $session->last_activity_formatted }}</small><br>
                                <small class="text-muted">{{ $session->last_activity_human }}</small>
                            </td>
                            <td>
                                @if($session->is_current)
                                    <span class="badge badge-success">Current Session</span>
                                @elseif($session->is_active)
                                    <span class="badge badge-info">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                @if(!$session->is_current)
                                <form action="{{ route('admin.sessions.revoke', $session->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to revoke this session?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-ban"></i> Revoke
                                    </button>
                                </form>
                                @else
                                <span class="text-muted">Current</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No sessions found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

