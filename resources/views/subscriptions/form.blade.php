@php
    $isEdit = isset($subscription);
    $actionUrl = $isEdit ? route('subscriptions.update', $subscription->id) : route('subscriptions.store');
@endphp

<form id="subscription-form" action="{{ $actionUrl }}" method="POST">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label for="user_id" class="form-label">Member <span class="text-danger">*</span></label>
            <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                <option value="" disabled {{ !$isEdit ? 'selected' : '' }}>Select Member</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id', $isEdit ? $subscription->user_id : '') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label for="plan_id" class="form-label">Plan <span class="text-danger">*</span></label>
            <select name="plan_id" id="plan_id" class="form-select @error('plan_id') is-invalid @enderror" required>
                <option value="" disabled {{ !$isEdit ? 'selected' : '' }}>Select Plan</option>
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}" {{ old('plan_id', $isEdit ? $subscription->plan_id : '') == $plan->id ? 'selected' : '' }}>
                        {{ $plan->service->name }} - {{ $plan->name }} ({{ number_format($plan->price, 2) }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
            <input type="date" name="start_date" id="start_date" class="form-control"
                   value="{{ old('start_date', $isEdit ? $subscription->start_date->format('Y-m-d') : date('Y-m-d')) }}" required>
        </div>
        <div class="col-md-6">
            <label for="end_date" class="form-label">End Date <small class="text-muted">(Optional, Auto-calculated)</small></label>
            <input type="date" name="end_date" id="end_date" class="form-control"
                   value="{{ old('end_date', $isEdit && $subscription->end_date ? $subscription->end_date->format('Y-m-d') : '') }}">
        </div>
    </div>

    <div class="mb-3">
        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
        <select name="status" id="status" class="form-select" required>
            <option value="active" {{ old('status', $isEdit ? $subscription->status : 'active') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="frozen" {{ old('status', $isEdit ? $subscription->status : '') == 'frozen' ? 'selected' : '' }}>Frozen</option>
            <option value="expired" {{ old('status', $isEdit ? $subscription->status : '') == 'expired' ? 'selected' : '' }}>Expired</option>
        </select>
    </div>

    <div class="text-end mt-4">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="save-btn">
            {{ $isEdit ? 'Update' : 'Create' }} Subscription
        </button>
    </div>
</form>
