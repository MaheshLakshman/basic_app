<form action="{{ route('admin.price_plans.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label class="form-label">Name (Translatable)</label>
        <input type="text" name="name[en]" class="form-control" placeholder="English Name" required>
        <!-- Add other languages if needed -->
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Organization</label>
            <select name="organization_id" class="form-select" required>
                @foreach($organizations as $org)
                    <option value="{{ $org->id }}">{{ $org->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Branch (Optional)</label>
            <select name="branch_id" class="form-select">
                <option value="">None</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Billing Type</label>
            <select name="billing_type" class="form-select" required>
                <option value="monthly">Monthly</option>
                <option value="usage">Usage</option>
                <option value="hourly">Hourly</option>
                <option value="package">Package</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Price</label>
            <input type="number" step="0.01" name="price" class="form-control" required>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Duration (Days)</label>
            <input type="number" name="duration_days" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Max Sessions</label>
            <input type="number" name="max_sessions" class="form-control">
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
    </div>
    <div class="text-end">
        <button type="submit" class="btn btn-primary">Create</button>
    </div>
</form>
