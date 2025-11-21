@if($pendingPledges->count() > 0)
<div class="card mb-4">
    <div class="card-header bg-warning text-dark">
        <h6 class="mb-0">
            <i class="fas fa-handshake me-2"></i>
            <strong>Pending Pledges ({{ $pendingPledges->count() }})</strong>
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm table-hover">
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Purpose</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingPledges as $pledge)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                    {{ substr($pledge->member->full_name ?? 'U', 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $pledge->member->full_name ?? 'Unknown' }}</div>
                                    <small class="text-muted">{{ $pledge->member->member_id ?? 'N/A' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ ucfirst($pledge->pledge_type) }}</span>
                        </td>
                        <td class="text-end">
                            <strong>TZS {{ number_format($pledge->pledge_amount, 0) }}</strong>
                        </td>
                        <td>{{ $pledge->pledge_date ? \Carbon\Carbon::parse($pledge->pledge_date)->format('M d, Y') : '-' }}</td>
                        <td>
                            <span class="text-truncate d-inline-block" style="max-width: 150px;" title="{{ $pledge->purpose }}">
                                {{ $pledge->purpose ?? '-' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                @if(isset($canApprove) && $canApprove)
                                    <button type="button" class="btn btn-outline-success" 
                                            onclick="approveRecord('pledge', {{ $pledge->id }})"
                                            title="Approve">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger" 
                                            onclick="rejectRecord('pledge', {{ $pledge->id }})"
                                            title="Reject">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif
                                <button type="button" class="btn btn-outline-info" 
                                        onclick="viewRecord('pledge', {{ $pledge->id }})"
                                        title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
