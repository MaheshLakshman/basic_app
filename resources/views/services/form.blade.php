@php
    $isEdit = isset($service);
    $actionUrl = $isEdit ? route('services.update', $service->id) : route('services.store');
@endphp

<form id="service-form" action="{{ $actionUrl }}" method="POST">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="mb-3">
        <label for="organization_id" class="form-label">Organization <span class="text-danger">*</span></label>
        <select name="organization_id" id="organization_id" class="form-select" required>
            <option value="" disabled {{ !$isEdit ? 'selected' : '' }}>Select Organization</option>
            @foreach($organizations as $org)
                <option value="{{ $org->id }}" {{ old('organization_id', $isEdit ? $service->organization_id : '') == $org->id ? 'selected' : '' }}>
                    {{ $org->name }}
                </option>
            @endforeach
        </select>
    </div>

    @foreach($languages as $language)
        <div class="mb-3">
            <label for="name_{{ $language->code }}" class="form-label">Name ({{ $language->name }}) <span class="text-danger">*</span></label>
            <input type="text" name="name[{{ $language->code }}]" id="name_{{ $language->code }}" 
                   class="form-control" 
                   value="{{ old('name.' . $language->code, $isEdit ? $service->getTranslation('name', $language->code, false) : '') }}" required>
        </div>
        
        <div class="mb-3">
            <label for="description_{{ $language->code }}" class="form-label">Description ({{ $language->name }})</label>
            <textarea name="description[{{ $language->code }}]" id="description_{{ $language->code }}" 
                      class="form-control" rows="2">{{ old('description.' . $language->code, $isEdit ? $service->getTranslation('description', $language->code, false) : '') }}</textarea>
        </div>
    @endforeach

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="service_type" class="form-label">Service Type <span class="text-danger">*</span></label>
            <select name="service_type" id="service_type" class="form-select" required>
                <option value="gym" {{ old('service_type', $isEdit ? $service->service_type : '') == 'gym' ? 'selected' : '' }}>Gym Access</option>
                <option value="pt" {{ old('service_type', $isEdit ? $service->service_type : '') == 'pt' ? 'selected' : '' }}>Personal Training</option>
                <option value="class" {{ old('service_type', $isEdit ? $service->service_type : '') == 'class' ? 'selected' : '' }}>Class</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="billing_type" class="form-label">Billing Type <span class="text-danger">*</span></label>
            <select name="billing_type" id="billing_type" class="form-select" required>
                <option value="subscription" {{ old('billing_type', $isEdit ? $service->billing_type : '') == 'subscription' ? 'selected' : '' }}>Subscription</option>
                <option value="usage" {{ old('billing_type', $isEdit ? $service->billing_type : '') == 'usage' ? 'selected' : '' }}>Usage Based</option>
                <option value="hybrid" {{ old('billing_type', $isEdit ? $service->billing_type : '') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
            </select>
        </div>
    </div>

    <div class="text-end mt-3">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="save-btn">
            {{ $isEdit ? 'Update' : 'Create' }} Service
        </button>
    </div>
</form>
