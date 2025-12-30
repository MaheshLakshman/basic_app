<form action="{{ route('admin.account_locks.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label class="form-label">User</label>
        <select name="user_id" class="form-select" required>
            <option value="">Select User</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Reason</label>
        <textarea name="reason" class="form-control" rows="3" required></textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Unlock At (Optional)</label>
        <input type="datetime-local" name="unlock_at" class="form-control">
    </div>
    <div class="text-end">
        <button type="submit" class="btn btn-danger">Lock Account</button>
    </div>
</form>
