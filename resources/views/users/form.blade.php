@php
    $isEdit = isset($user);
    $actionUrl = $isEdit ? route('users.update', $user->id) : route('users.store');
@endphp

<form id="user-form" action="{{ $actionUrl }}" method="POST">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" name="name" id="name" class="form-control" 
                   value="{{ old('name', $isEdit ? $user->name : '') }}" required>
        </div>
        <div class="col-md-6">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" name="email" id="email" class="form-control" 
                   value="{{ old('email', $isEdit ? $user->email : '') }}" required>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label for="password" class="form-label">Password {{ $isEdit ? '(Leave blank to keep current)' : '' }}</label>
            <input type="password" name="password" id="password" class="form-control" 
                   {{ $isEdit ? '' : 'required' }}>
        </div>
        <div class="col-md-6">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" 
                   {{ $isEdit ? '' : 'required' }}>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label d-block">Roles</label>
        <div class="row g-2">
            @foreach($roles as $role)
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="roles[]" 
                               value="{{ $role->name }}" id="role-{{ $role->id }}"
                               {{ (isset($userRoles) && in_array($role->name, $userRoles)) ? 'checked' : '' }}>
                        <label class="form-check-label small" for="role-{{ $role->id }}">
                            {{ $role->name }}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label d-block text-primary fw-bold">Direct Permissions (Assign independently of roles)</label>
        <div class="row g-2">
            @foreach($permissions as $permission)
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="permissions[]" 
                               value="{{ $permission->name }}" id="user-perm-{{ $permission->id }}"
                               {{ (isset($userPermissions) && in_array($permission->name, $userPermissions)) ? 'checked' : '' }}>
                        <label class="form-check-label small" for="user-perm-{{ $permission->id }}">
                            {{ $permission->name }}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="text-end mt-4">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                data-bs-toggle="tooltip" title="Exit without saving">Close</button>
        <button type="submit" class="btn btn-primary" id="save-btn"
                data-bs-toggle="tooltip" title="Save user settings">
            {{ $isEdit ? 'Update' : 'Create' }} User
        </button>
    </div>
</form>
