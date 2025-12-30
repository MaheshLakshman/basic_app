<form action="{{ route('admin.price_adjustments.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label class="form-label">Member</label>
        <select name="member_id" class="form-select" required>
            <option value="">Select Member</option>
            @foreach($members as $member)
                <option value="{{ $member->id }}">{{ $member->name }} ({{ $member->email }})</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Type</label>
        <select name="type" class="form-select" required>
            <option value="discount">Discount</option>
            <option value="penalty">Penalty</option>
            <option value="waiver">Waiver</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Amount</label>
        <input type="number" step="0.01" name="amount" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Applied Month (Optional)</label>
        <input type="date" name="applied_month" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">Reason</label>
        <textarea name="reason" class="form-control" rows="2"></textarea>
    </div>
    <div class="text-end">
        <button type="submit" class="btn btn-primary">Create</button>
    </div>
</form>
