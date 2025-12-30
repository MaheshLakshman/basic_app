<form action="{{ route('admin.member_price_assignments.store') }}" method="POST">
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
        <label class="form-label">Service & Plan</label>
        <select name="plan_id" class="form-select" required>
            <option value="">Select Plan</option>
            @foreach($plans as $plan)
                <option value="{{ $plan->id }}">
                    {{ $plan->service->getTranslation('name', app()->getLocale()) ?? $plan->service->name }} - 
                    {{ $plan->getTranslation('name', app()->getLocale()) ?? $plan->name }} 
                    ({{ $plan->price }})
                </option>
            @endforeach
        </select>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Start Date</label>
            <input type="date" name="start_date" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">End Date</label>
            <input type="date" name="end_date" class="form-control">
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Custom Price (Optional)</label>
        <input type="number" step="0.01" name="custom_price" class="form-control">
    </div>
    <div class="text-end">
        <button type="submit" class="btn btn-primary">Assign</button>
    </div>
</form>
