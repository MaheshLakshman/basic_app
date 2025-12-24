@php
    $isEdit = isset($organization);
    $actionUrl = $isEdit ? route('organizations.update', $organization->id) : route('organizations.store');
@endphp

<form id="organization-form" action="{{ $actionUrl }}" method="POST">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    @foreach($languages as $language)
        <div class="mb-3">
            <label for="name_{{ $language->code }}" class="form-label">Name ({{ $language->name }})</label>
            <input type="text" name="name[{{ $language->code }}]" id="name_{{ $language->code }}" 
                   class="form-control @error('name.' . $language->code) is-invalid @enderror" 
                   value="{{ old('name.' . $language->code, $isEdit ? $organization->getTranslation('name', $language->code) : '') }}">
            @error('name.' . $language->code)
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    @endforeach

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="timezone" class="form-label">Timezone</label>
            <select name="timezone" id="timezone" class="form-select @error('timezone') is-invalid @enderror">
                <option value="">Select Timezone</option>
                <option value="UTC" {{ old('timezone', $isEdit ? $organization->timezone : '') == 'UTC' ? 'selected' : '' }}>UTC</option>
                <option value="America/New_York" {{ old('timezone', $isEdit ? $organization->timezone : '') == 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                <option value="Europe/London" {{ old('timezone', $isEdit ? $organization->timezone : '') == 'Europe/London' ? 'selected' : '' }}>Europe/London</option>
                <option value="Asia/Dubai" {{ old('timezone', $isEdit ? $organization->timezone : '') == 'Asia/Dubai' ? 'selected' : '' }}>Asia/Dubai</option>
            </select>
            @error('timezone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label for="currency" class="form-label">Currency</label>
            <input type="text" name="currency" id="currency" class="form-control @error('currency') is-invalid @enderror" 
                   value="{{ old('currency', $isEdit ? $organization->currency : '') }}" placeholder="USD" maxlength="3">
            @error('currency')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="mb-4">
        <label for="status" class="form-label">Status</label>
        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
            <option value="active" {{ old('status', $isEdit ? $organization->status : 'active') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $isEdit ? $organization->status : '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="text-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                data-bs-toggle="tooltip" title="Click to discard and close">Close</button>
        <button type="submit" class="btn btn-primary" id="save-btn"
                data-bs-toggle="tooltip" title="Save organization details">
            {{ $isEdit ? 'Update' : 'Create' }} Organization
        </button>
    </div>
</form>
