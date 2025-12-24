@php
    $isEdit = isset($permission);
    $actionUrl = $isEdit ? route('permissions.update', $permission->id) : route('permissions.store');
@endphp

<form id="permission-form" action="{{ $actionUrl }}" method="POST">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="mb-3">
        <label for="name" class="form-label">Permission Name</label>
        <input type="text" name="name" id="name" class="form-control" 
               value="{{ old('name', $isEdit ? $permission->name : '') }}" required>
    </div>

    <div class="text-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" 
                data-bs-toggle="tooltip" title="Discard changes and close">Close</button>
        <button type="submit" class="btn btn-primary" id="save-btn"
                data-bs-toggle="tooltip" title="{{ $isEdit ? 'Update existing' : 'Create new' }} permission">
            {{ $isEdit ? 'Update' : 'Create' }} Permission
        </button>
    </div>
</form>
