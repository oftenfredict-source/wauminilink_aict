@extends('layouts.index')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm dashboard-header" style="background:#17082d;">
                <div class="card-body text-white py-2 px-3">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center border border-white border-2" style="width:48px; height:48px; background:rgba(255,255,255,.15);">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <div class="lh-sm">
                                <h5 class="mb-0 fw-semibold" style="color: white !important;">User Activity: {{ $user->name }}</h5>
                                <small style="color: white !important;">{{ $user->email }} - {{ ucfirst($user->role) }}</small>
                            </div>
                        </div>
                        <a href="{{ route('admin.users') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Users
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Activity Logs ({{ $activities->total() }} total)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Action</th>
                            <th>Description</th>
                            <th>Route</th>
                            <th>IP Address</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities as $activity)
                        <tr>
                            <td>{{ $activity->id }}</td>
                            <td>
                                <span class="badge badge-{{ $activity->action === 'create' ? 'success' : ($activity->action === 'delete' ? 'danger' : ($activity->action === 'approve' ? 'warning' : 'info')) }}">
                                    {{ ucfirst($activity->action) }}
                                </span>
                            </td>
                            <td>{{ $activity->description }}</td>
                            <td><small>{{ $activity->route ?? 'N/A' }}</small></td>
                            <td><small>{{ $activity->ip_address }}</small></td>
                            <td>
                                <small>{{ $activity->created_at->format('Y-m-d H:i:s') }}</small><br>
                                <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No activities found for this user</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-3">
                {{ $activities->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

