@php
    $isEdit = isset($role);
    $actionUrl = $isEdit ? route('roles.update', $role->id) : route('roles.store');
@endphp

<form id="role-form" action="{{ $actionUrl }}" method="POST">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="mb-3">
        <label for="name" class="form-label">Role Name</label>
        <input type="text" name="name" id="name" class="form-control" 
               value="{{ old('name', $isEdit ? $role->name : '') }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label d-block">Permissions</label>
        <div class="row g-2">
            @foreach($permissions as $permission)
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="permissions[]" 
                               value="{{ $permission->name }}" id="perm-{{ $permission->id }}"
                               {{ (isset($rolePermissions) && in_array($permission->name, $rolePermissions)) ? 'checked' : '' }}>
                        <label class="form-check-label small" for="perm-{{ $permission->id }}">
                            {{ $permission->name }}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="text-end mt-4">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                data-bs-toggle="tooltip" title="Discard changes and exit">Close</button>
        <button type="submit" class="btn btn-primary" id="save-btn"
                data-bs-toggle="tooltip" title="Save this role and its permissions">
            {{ $isEdit ? 'Update' : 'Create' }} Role
        </button>
    </div>
</form>
