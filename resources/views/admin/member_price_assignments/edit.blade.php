<form action="{{ route('admin.member_price_assignments.update', $memberPriceAssignment->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">Member</label>
        <select name="member_id" class="form-select" required>
            @foreach($members as $member)
                <option value="{{ $member->id }}" {{ $memberPriceAssignment->member_id == $member->id ? 'selected' : '' }}>{{ $member->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Price Plan</label>
        <select name="price_plan_id" class="form-select" required>
            @foreach($plans as $plan)
                <option value="{{ $plan->id }}" {{ $memberPriceAssignment->price_plan_id == $plan->id ? 'selected' : '' }}>{{ $plan->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Start Date</label>
            <input type="date" name="start_date" class="form-control" value="{{ $memberPriceAssignment->start_date ? $memberPriceAssignment->start_date->format('Y-m-d') : '' }}" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">End Date</label>
            <input type="date" name="end_date" class="form-control" value="{{ $memberPriceAssignment->end_date ? $memberPriceAssignment->end_date->format('Y-m-d') : '' }}">
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Custom Price (Optional)</label>
        <input type="number" step="0.01" name="custom_price" class="form-control" value="{{ $memberPriceAssignment->custom_price }}">
    </div>
    <div class="mb-3">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActiveSwitch" {{ $memberPriceAssignment->is_active ? 'checked' : '' }}>
            <label class="form-check-label" for="isActiveSwitch">Active</label>
        </div>
    </div>
    <div class="text-end">
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</form>
