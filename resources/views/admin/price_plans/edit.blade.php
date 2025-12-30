<form action="{{ route('admin.price_plans.update', $pricePlan->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">Name (Translatable)</label>
        <input type="text" name="name[en]" class="form-control" value="{{ $pricePlan->getTranslation('name', 'en') }}" placeholder="English Name" required>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Organization</label>
            <select name="organization_id" class="form-select" required>
                @foreach($organizations as $org)
                    <option value="{{ $org->id }}" {{ $pricePlan->organization_id == $org->id ? 'selected' : '' }}>{{ $org->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Branch (Optional)</label>
            <select name="branch_id" class="form-select">
                <option value="">None</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" {{ $pricePlan->branch_id == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Billing Type</label>
            <select name="billing_type" class="form-select" required>
                <option value="monthly" {{ $pricePlan->billing_type == 'monthly' ? 'selected' : '' }}>Monthly</option>
                <option value="usage" {{ $pricePlan->billing_type == 'usage' ? 'selected' : '' }}>Usage</option>
                <option value="hourly" {{ $pricePlan->billing_type == 'hourly' ? 'selected' : '' }}>Hourly</option>
                <option value="package" {{ $pricePlan->billing_type == 'package' ? 'selected' : '' }}>Package</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Price</label>
            <input type="number" step="0.01" name="price" class="form-control" value="{{ $pricePlan->price }}" required>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Duration (Days)</label>
            <input type="number" name="duration_days" class="form-control" value="{{ $pricePlan->duration_days }}" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Max Sessions</label>
            <input type="number" name="max_sessions" class="form-control" value="{{ $pricePlan->max_sessions }}">
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="active" {{ $pricePlan->status == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ $pricePlan->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
    <div class="text-end">
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</form>
