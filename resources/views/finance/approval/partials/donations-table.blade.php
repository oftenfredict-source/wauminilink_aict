@if($records->count() > 0)
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                @if(isset($canApprove) && $canApprove)
                    <th>
                        <input type="checkbox" id="selectAllDonations" onchange="toggleAllDonations()">
                    </th>
                @endif
                <th>Donor</th>
                <th>Amount</th>
                <th>Type</th>
                <th>Purpose</th>
                <th>Date</th>
                <th>Payment Method</th>
                <th>Reference</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
            <tr data-record-type="donation" data-record-id="{{ $record->id }}">
                @if(isset($canApprove) && $canApprove)
                    <td>
                        <input type="checkbox" class="donation-checkbox" value="{{ $record->id }}">
                    </td>
                @endif
                <td>
                    @if($record->member)
                        <strong>{{ $record->member->full_name }}</strong>
                        <br><small class="text-muted">{{ $record->member->member_id }}</small>
                    @elseif($record->donor_name)
                        <strong>{{ $record->donor_name }}</strong>
                        @if($record->is_anonymous)
                            <br><small class="text-muted">Anonymous</small>
                        @endif
                    @else
                        <span class="text-muted">Anonymous</span>
                    @endif
                </td>
                <td>
                    <strong class="text-success">TZS {{ number_format($record->amount, 0) }}</strong>
                </td>
                <td>
                    <span class="badge badge-primary">
                        @if(in_array($record->donation_type, ['general', 'building', 'mission', 'special']))
                            {{ ucfirst(str_replace('_', ' ', $record->donation_type)) }}
                        @else
                            {{ ucfirst($record->donation_type) }}
                        @endif
                    </span>
                </td>
                <td>{{ $record->purpose ?? 'General' }}</td>
                <td>{{ $record->donation_date ? \Carbon\Carbon::parse($record->donation_date)->format('M d, Y') : '-' }}</td>
                <td>
                    <span class="badge bg-info">{{ ucfirst($record->payment_method) }}</span>
                </td>
                <td>{{ $record->reference_number ?? 'N/A' }}</td>
                <td>
                    <div class="btn-group btn-group-sm">
                        @if(isset($canApprove) && $canApprove)
                            <button class="btn btn-success btn-sm" onclick="approveRecord('donation', {{ $record->id }})" title="Approve">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="rejectRecord('donation', {{ $record->id }})" title="Reject">
                                <i class="fas fa-times"></i>
                            </button>
                        @endif
                        <button class="btn btn-info btn-sm" onclick="viewDetails('donation', {{ $record->id }})" title="View Details">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="text-center py-4">
    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
    <h5>No Pending Donations</h5>
    <p class="text-muted">All donations for today have been processed.</p>
</div>
@endif

<script>
function toggleAllDonations() {
    const selectAll = document.getElementById('selectAllDonations');
    const checkboxes = document.querySelectorAll('.donation-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}
</script>


