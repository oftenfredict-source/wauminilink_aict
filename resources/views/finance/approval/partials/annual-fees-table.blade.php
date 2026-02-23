@if($records->count() > 0)
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    @if(isset($canApprove) && $canApprove)
                        <th>
                            <input type="checkbox" id="selectAllAnnualFees" onchange="toggleAllAnnualFees()">
                        </th>
                    @endif
                    <th>Member</th>
                    <th>Year</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Payment Method</th>
                    <th>Reference</th>
                    <th>Recorded By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($records as $record)
                    <tr data-record-type="annual_fee" data-record-id="{{ $record->id }}">
                        @if(isset($canApprove) && $canApprove)
                            <td>
                                <input type="checkbox" class="annual-fee-checkbox" value="{{ $record->id }}">
                            </td>
                        @endif
                        <td>
                            @if($record->member)
                                <strong>{{ $record->member->full_name }}</strong>
                                <br><small class="text-muted">{{ $record->member->member_id }}</small>
                            @elseif($record->child)
                                <strong>{{ $record->child->full_name }}</strong>
                                <br><small class="text-muted">Child ID: {{ $record->child->id }}</small>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td><span class="badge bg-primary">{{ $record->year }}</span></td>
                        <td>
                            <strong class="text-success">TZS {{ number_format($record->amount, 0) }}</strong>
                        </td>
                        <td>{{ $record->payment_date ? \Carbon\Carbon::parse($record->payment_date)->format('M d, Y') : '-' }}
                        </td>
                        <td>
                            <span class="badge bg-info">{{ ucfirst($record->payment_method) }}</span>
                        </td>
                        <td>{{ $record->reference_number ?? 'N/A' }}</td>
                        <td>{{ $record->recorded_by }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                @if(isset($canApprove) && $canApprove)
                                    <button class="btn btn-success btn-sm" onclick="approveRecord('annual_fee', {{ $record->id }})"
                                        title="Approve">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="rejectRecord('annual_fee', {{ $record->id }})"
                                        title="Reject">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif
                                <button class="btn btn-info btn-sm" onclick="viewDetails('annual_fee', {{ $record->id }})"
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
@else
    <div class="text-center py-4">
        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
        <h5>No Pending Annual Fees</h5>
        <p class="text-muted">All annual fee payments have been processed.</p>
    </div>
@endif

<script>
    function toggleAllAnnualFees() {
        const selectAll = document.getElementById('selectAllAnnualFees');
        const checkboxes = document.querySelectorAll('.annual-fee-checkbox');

        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAll.checked;
        });
    }
</script>