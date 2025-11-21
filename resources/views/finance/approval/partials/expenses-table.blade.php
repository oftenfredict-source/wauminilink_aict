@if($records->count() > 0)
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                @if(isset($canApprove) && $canApprove)
                    <th>
                        <input type="checkbox" id="selectAllExpenses" onchange="toggleAllExpenses()">
                    </th>
                @endif
                <th>Expense Name</th>
                <th>Category</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Vendor</th>
                <th>Budget</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
            <tr data-record-type="expense" data-record-id="{{ $record->id }}">
                @if(isset($canApprove) && $canApprove)
                    <td>
                        <input type="checkbox" class="expense-checkbox" value="{{ $record->id }}">
                    </td>
                @endif
                <td>
                    <strong>{{ $record->expense_name }}</strong>
                    @if($record->description)
                        <br><small class="text-muted">{{ Str::limit($record->description, 50) }}</small>
                    @endif
                </td>
                <td>
                    <span class="badge badge-secondary">{{ ucfirst($record->expense_category) }}</span>
                </td>
                <td>
                    <strong class="text-danger">TZS {{ number_format($record->amount, 0) }}</strong>
                </td>
                <td>{{ $record->expense_date ? \Carbon\Carbon::parse($record->expense_date)->format('M d, Y') : '-' }}</td>
                <td>{{ $record->vendor ?? 'N/A' }}</td>
                <td>
                    @if($record->budget)
                        <span class="badge bg-info">{{ $record->budget->budget_name }}</span>
                    @else
                        <span class="text-muted">No Budget</span>
                    @endif
                </td>
                <td>
                    <span class="badge badge-warning">{{ ucfirst($record->status) }}</span>
                </td>
                <td>
                    <div class="btn-group btn-group-sm">
                        @if(isset($canApprove) && $canApprove)
                            <button class="btn btn-success btn-sm" onclick="approveRecord('expense', {{ $record->id }})" title="Approve">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="rejectRecord('expense', {{ $record->id }})" title="Reject">
                                <i class="fas fa-times"></i>
                            </button>
                        @endif
                        <button class="btn btn-info btn-sm" onclick="viewDetails('expense', {{ $record->id }})" title="View Details">
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
    <h5>No Pending Expenses</h5>
    <p class="text-muted">All expenses for today have been processed.</p>
</div>
@endif

<script>
function toggleAllExpenses() {
    const selectAll = document.getElementById('selectAllExpenses');
    const checkboxes = document.querySelectorAll('.expense-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}
</script>


