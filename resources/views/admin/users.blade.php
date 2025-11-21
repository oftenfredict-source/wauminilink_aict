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
                                <i class="fas fa-users text-white"></i>
                            </div>
                            <div class="lh-sm">
                                <h5 class="mb-0 fw-semibold" style="color: white !important;">User Management</h5>
                                <small style="color: white !important;">Manage system users</small>
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

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Users ({{ $users->count() }} total)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Can Approve Finances</th>
                            <th>Activities</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>
                                <strong>{{ $user->name }}</strong>
                                @if($user->is_login_blocked)
                                <br><span class="badge badge-danger" style="font-size: 0.7em; margin-top: 2px;">
                                    <i class="fas fa-ban"></i> Login Blocked ({{ $user->remaining_block_time }} min)
                                </span>
                                @endif
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @php
                                    $roleBadgeClass = match($user->role) {
                                        'admin' => 'badge-danger',
                                        'pastor' => 'badge-warning',
                                        'secretary' => 'badge-info',
                                        'treasurer' => 'badge-secondary',
                                        default => 'badge-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $roleBadgeClass }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                @if($user->can_approve_finances)
                                    <span class="badge badge-success">Yes</span>
                                @else
                                    <span class="badge badge-secondary">No</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.user-activity', $user->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-list"></i> {{ $user->activity_logs_count }} Activities
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('admin.user-activity', $user->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> View Activity
                                </a>
                                @if($user->is_login_blocked)
                                <form action="{{ route('admin.users.unblock', $user->id) }}" method="POST" class="d-inline mt-1" onsubmit="return confirm('Unblock this user from logging in?');">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="fas fa-unlock"></i> Unblock
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No users found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

