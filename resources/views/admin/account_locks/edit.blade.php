<form action="{{ route('admin.account_locks.update', $accountLock->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">User</label>
        <input type="text" class="form-control" value="{{ $accountLock->user->name }}" disabled>
    </div>
    <div class="mb-3">
        <label class="form-label">Reason</label>
        <textarea name="reason" class="form-control" rows="3" required>{{ $accountLock->reason }}</textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Unlock At</label>
        <input type="datetime-local" name="unlock_at" class="form-control" value="{{ $accountLock->unlock_at ? $accountLock->unlock_at->format('Y-m-d\TH:i') : '' }}">
    </div>
    <div class="mb-3">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActiveSwitch" {{ $accountLock->is_active ? 'checked' : '' }}>
            <label class="form-check-label" for="isActiveSwitch">Lock Active</label>
        </div>
    </div>
    <div class="text-end">
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</form>
