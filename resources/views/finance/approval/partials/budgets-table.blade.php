@if($records->count() > 0)
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                @if(isset($canApprove) && $canApprove)
                    <th>
                        <input type="checkbox" id="selectAllBudgets" onchange="toggleAllBudgets()">
                    </th>
                @endif
                <th>Budget Name</th>
                <th>Type</th>
                <th>Total Budget</th>
                <th>Fiscal Year</th>
                <th>Period</th>
                <th>Status</th>
                <th>Created By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
            <tr data-record-type="budget" data-record-id="{{ $record->id }}">
                @if(isset($canApprove) && $canApprove)
                    <td>
                        <input type="checkbox" class="budget-checkbox" value="{{ $record->id }}">
                    </td>
                @endif
                <td>
                    <strong>{{ $record->budget_name }}</strong>
                    @if($record->description)
                        <br><small class="text-muted">{{ Str::limit($record->description, 50) }}</small>
                    @endif
                </td>
                <td>
                    <span class="badge badge-primary">{{ ucfirst($record->budget_type) }}</span>
                </td>
                <td>
                    <strong class="text-info">TZS {{ number_format($record->total_budget, 0) }}</strong>
                </td>
                <td>
                    <span class="badge badge-secondary">{{ $record->fiscal_year }}</span>
                </td>
                <td>
                    {{ $record->start_date ? \Carbon\Carbon::parse($record->start_date)->format('M d') : '-' }} - {{ $record->end_date ? \Carbon\Carbon::parse($record->end_date)->format('M d, Y') : '-' }}
                </td>
                <td>
                    <span class="badge badge-warning">{{ ucfirst($record->status) }}</span>
                </td>
                <td>{{ $record->created_by }}</td>
                <td>
                    <div class="btn-group btn-group-sm">
                        @if(isset($canApprove) && $canApprove)
                            <button class="btn btn-success btn-sm" onclick="approveRecord('budget', {{ $record->id }})" title="Approve">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="rejectRecord('budget', {{ $record->id }})" title="Reject">
                                <i class="fas fa-times"></i>
                            </button>
                        @endif
                        <button class="btn btn-info btn-sm" onclick="viewDetails('budget', {{ $record->id }})" title="View Details">
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
    <h5>No Pending Budgets</h5>
    <p class="text-muted">All budgets for today have been processed.</p>
</div>
@endif

<script>
function toggleAllBudgets() {
    const selectAll = document.getElementById('selectAllBudgets');
    const checkboxes = document.querySelectorAll('.budget-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}
</script>


