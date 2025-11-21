@extends('layouts.index')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm dashboard-header" style="background:white;">
                <div class="card-body py-2 px-3">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center border border-primary border-2" style="width:48px; height:48px; background:rgba(0,123,255,.1);">
                                <i class="fas fa-bullhorn text-primary"></i>
                            </div>
                            <div class="lh-sm">
                                <h5 class="mb-0 fw-semibold text-dark">Announcements</h5>
                                <small class="text-muted">Manage church announcements</small>
                            </div>
                        </div>
                        <a href="{{ route('announcements.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-2"></i>Create Announcement
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if($announcements->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($announcements as $announcement)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($announcement->is_pinned)
                                                <i class="fas fa-thumbtack text-warning me-2" title="Pinned"></i>
                                            @endif
                                            <strong>{{ $announcement->title }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $announcement->type === 'urgent' ? 'danger' : ($announcement->type === 'event' ? 'success' : ($announcement->type === 'reminder' ? 'warning' : 'info')) }}">
                                            {{ ucfirst($announcement->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($announcement->isCurrentlyActive())
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $announcement->start_date ? $announcement->start_date->format('M d, Y') : 'Immediate' }}</td>
                                    <td>{{ $announcement->end_date ? $announcement->end_date->format('M d, Y') : 'No expiry' }}</td>
                                    <td>{{ $announcement->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('announcements.edit', $announcement) }}" class="btn btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('announcements.send-sms', $announcement) }}" method="POST" class="d-inline" onsubmit="return confirm('Send SMS notification to all members for this announcement?');">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-info" title="Send SMS">
                                                    <i class="fas fa-sms"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('announcements.destroy', $announcement) }}" method="POST" class="d-inline delete-announcement-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $announcements->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-bullhorn fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No announcements yet. Create your first announcement!</p>
                    <a href="{{ route('announcements.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create Announcement
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle delete announcement with SweetAlert
        const deleteForms = document.querySelectorAll('.delete-announcement-form');
        
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const form = this;
                const announcementTitle = form.closest('tr').querySelector('td strong')?.textContent || 'this announcement';
                
                Swal.fire({
                    title: 'Delete Announcement?',
                    html: `<p>Are you sure you want to delete <strong>"${announcementTitle}"</strong>?</p><p class="text-danger">This action cannot be undone.</p>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-trash me-1"></i>Yes, delete it',
                    cancelButtonText: '<i class="fas fa-times me-1"></i>Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        Swal.fire({
                            title: 'Deleting...',
                            text: 'Please wait',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // Submit the form
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection

