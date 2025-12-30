@php
    $isEdit = isset($plan);
    $actionUrl = $isEdit ? route('plans.update', $plan->id) : route('plans.store');
@endphp

<form id="plan-form" action="{{ $actionUrl }}" method="POST">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="mb-3">
        <label for="service_id" class="form-label">Service <span class="text-danger">*</span></label>
        <select name="service_id" id="service_id" class="form-select @error('service_id') is-invalid @enderror" required>
            <option value="" disabled {{ !$isEdit ? 'selected' : '' }}>Select Service</option>
            @foreach($services as $service)
                <option value="{{ $service->id }}" {{ old('service_id', $isEdit ? $plan->service_id : '') == $service->id ? 'selected' : '' }}>
                    {{ $service->name }} ({{ $service->organization->name ?? '-' }})
                </option>
            @endforeach
        </select>
        @error('service_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    @foreach($languages as $language)
        <div class="mb-3">
            <label for="name_{{ $language->code }}" class="form-label">Name ({{ $language->name }}) <span class="text-danger">*</span></label>
            <input type="text" name="name[{{ $language->code }}]" id="name_{{ $language->code }}" 
                   class="form-control" 
                   value="{{ old('name.' . $language->code, $isEdit ? $plan->getTranslation('name', $language->code, false) : '') }}" required>
        </div>
    @endforeach

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
            <input type="number" step="0.01" name="price" id="price" class="form-control" 
                   value="{{ old('price', $isEdit ? $plan->price : '') }}" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="usage_limit" class="form-label">Usage Limit (Optional)</label>
            <input type="number" name="usage_limit" id="usage_limit" class="form-control" 
                   value="{{ old('usage_limit', $isEdit ? $plan->usage_limit : '') }}" placeholder="Null for unlimited">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="duration_value" class="form-label">Duration Value <span class="text-danger">*</span></label>
            <input type="number" name="duration_value" id="duration_value" class="form-control" 
                   value="{{ old('duration_value', $isEdit ? $plan->duration_value : '') }}" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="duration_type" class="form-label">Duration Type <span class="text-danger">*</span></label>
            <select name="duration_type" id="duration_type" class="form-select" required>
                <option value="day" {{ old('duration_type', $isEdit ? $plan->duration_type : '') == 'day' ? 'selected' : '' }}>Day(s)</option>
                <option value="month" {{ old('duration_type', $isEdit ? $plan->duration_type : '') == 'month' ? 'selected' : '' }}>Month(s)</option>
                <option value="year" {{ old('duration_type', $isEdit ? $plan->duration_type : '') == 'year' ? 'selected' : '' }}>Year(s)</option>
            </select>
        </div>
    </div>

    <div class="text-end mt-3">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="save-btn">
            {{ $isEdit ? 'Update' : 'Create' }} Plan
        </button>
    </div>
</form>
