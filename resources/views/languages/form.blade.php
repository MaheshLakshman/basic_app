@php
    $isEdit = isset($language);
    $actionUrl = $isEdit ? route('languages.update', $language->id) : route('languages.store');
@endphp

<form id="language-form" action="{{ $actionUrl }}" method="POST">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="mb-3">
        <label for="name" class="form-label">Language Name</label>
        <input type="text" name="name" id="name" class="form-control" 
               value="{{ old('name', $isEdit ? $language->name : '') }}" >
    </div>

    <div class="mb-3">
        <label for="code" class="form-label">Language Code (e.g., en, ar)</label>
        <input type="text" name="code" id="code" class="form-control" 
               value="{{ old('code', $isEdit ? $language->code : '') }}" maxlength="10">
    </div>

    <div class="mb-4">
        <label for="is_active" class="form-label">Status</label>
        <select name="is_active" id="is_active" class="form-select">
            <option value="1" {{ old('is_active', $isEdit ? $language->is_active : '1') == '1' ? 'selected' : '' }}>Active</option>
            <option value="0" {{ old('is_active', $isEdit ? $language->is_active : '') == '0' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>

    <div class="text-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                data-bs-toggle="tooltip" title="Cancel and close this form">Close</button>
        <button type="submit" class="btn btn-primary" id="save-btn"
                data-bs-toggle="tooltip" title="Save language information">
            {{ $isEdit ? 'Update' : 'Create' }} Language
        </button>
    </div>
</form>
