@if($pendingPledgePayments->count() > 0)
<div class="card mb-4">
    <div class="card-header bg-warning text-dark">
        <h6 class="mb-0">
            <i class="fas fa-handshake me-2"></i>
            <strong>Pending Pledge Payments ({{ $pendingPledgePayments->count() }})</strong>
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm table-hover">
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Pledge Type</th>
                        <th>Payment Amount</th>
                        <th>Payment Date</th>
                        <th>Payment Method</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingPledgePayments as $payment)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                    {{ substr($payment->pledge->member->full_name ?? 'U', 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $payment->pledge->member->full_name ?? 'Unknown' }}</div>
                                    <small class="text-muted">{{ $payment->pledge->member->member_id ?? 'N/A' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ ucfirst($payment->pledge->pledge_type ?? 'N/A') }}</span>
                        </td>
                        <td class="text-end">
                            <strong>TZS {{ number_format($payment->amount, 0) }}</strong>
                        </td>
                        <td>{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') : '-' }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ ucfirst($payment->payment_method ?? 'Cash') }}</span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                @if(isset($canApprove) && $canApprove)
                                    <button type="button" class="btn btn-outline-success" 
                                            onclick="approveRecord('pledge_payment', {{ $payment->id }})"
                                            title="Approve">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger" 
                                            onclick="rejectRecord('pledge_payment', {{ $payment->id }})"
                                            title="Reject">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif
                                <button type="button" class="btn btn-outline-info" 
                                        onclick="viewDetails('pledge_payment', {{ $payment->id }})"
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
@else
<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i>
    No pending pledge payments for today.
</div>
@endif







