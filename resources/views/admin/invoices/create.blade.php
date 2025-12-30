<form action="{{ route('admin.invoices.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label class="form-label">Member</label>
        <select name="user_id" class="form-select" required>
            <option value="">Select Member</option>
            @foreach($members as $member)
                <option value="{{ $member->id }}">{{ $member->name }} ({{ $member->email }})</option>
            @endforeach
        </select>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Period Start</label>
            <input type="date" name="period_start" class="form-control" value="{{ now()->startOfMonth()->format('Y-m-d') }}" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Period End</label>
            <input type="date" name="period_end" class="form-control" value="{{ now()->endOfMonth()->format('Y-m-d') }}" required>
        </div>
    </div>
    <div class="alert alert-info">
        <small><i class="bi bi-info-circle"></i> This will calculate the invoice based on the member's assigned plans and attendance during the selected period.</small>
    </div>
    <div class="text-end">
        <button type="submit" class="btn btn-primary">Generate Invoice</button>
    </div>
</form>
