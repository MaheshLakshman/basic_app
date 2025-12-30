@php
    $isEdit = isset($branch);
    $actionUrl = $isEdit ? route('branches.update', $branch->id) : route('branches.store');
@endphp

<form id="branch-form" action="{{ $actionUrl }}" method="POST">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="mb-3">
        <label for="organization_id" class="form-label">Organization <span class="text-danger">*</span></label>
        <select name="organization_id" id="organization_id" class="form-select @error('organization_id') is-invalid @enderror" required>
            <option value="" disabled {{ !$isEdit ? 'selected' : '' }}>Select Organization</option>
            @foreach($organizations as $org)
                <option value="{{ $org->id }}" {{ old('organization_id', $isEdit ? $branch->organization_id : '') == $org->id ? 'selected' : '' }}>
                    {{ $org->name }}
                </option>
            @endforeach
        </select>
        @error('organization_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="parent_id" class="form-label">Parent Branch (Optional)</label>
        <select name="parent_id" id="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
            <option value="" selected>None</option>
            @foreach($branches as $b)
                <option value="{{ $b->id }}" {{ old('parent_id', $isEdit ? $branch->parent_id : '') == $b->id ? 'selected' : '' }}>
                    {{ $b->name }}
                </option>
            @endforeach
        </select>
        @error('parent_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    @foreach($languages as $language)
        <div class="mb-3">
            <label for="name_{{ $language->code }}" class="form-label">Name ({{ $language->name }}) <span class="text-danger">*</span></label>
            <input type="text" name="name[{{ $language->code }}]" id="name_{{ $language->code }}" 
                   class="form-control @error('name.' . $language->code) is-invalid @enderror" 
                   value="{{ old('name.' . $language->code, $isEdit ? $branch->getTranslation('name', $language->code, false) : '') }}" required>
            @error('name.' . $language->code)
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    @endforeach

    @foreach($languages as $language)
        <div class="mb-3">
            <label for="address_{{ $language->code }}" class="form-label">Address ({{ $language->name }}) <span class="text-danger">*</span></label>
            <textarea name="address[{{ $language->code }}]" id="address_{{ $language->code }}" rows="3"
                   class="form-control @error('address.' . $language->code) is-invalid @enderror" required>{{ old('address.' . $language->code, $isEdit ? $branch->getTranslation('address', $language->code, false) : '') }}</textarea>
            @error('address.' . $language->code)
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    @endforeach

    <div class="mb-4">
        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="active" {{ old('status', $isEdit ? $branch->status : 'active') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $isEdit ? $branch->status : '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="text-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="save-btn">
            {{ $isEdit ? 'Update' : 'Create' }} Branch
        </button>
    </div>
</form>
