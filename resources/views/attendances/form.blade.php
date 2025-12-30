@php
    $isEdit = isset($attendance);
    $actionUrl = $isEdit ? route('attendances.update', $attendance->id) : route('attendances.store');
@endphp

<form id="attendance-form" action="{{ $actionUrl }}" method="POST">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="attendance_day" class="form-label">Date <span class="text-danger">*</span></label>
            <input type="date" name="attendance_day" id="attendance_day" class="form-control" 
                   value="{{ old('attendance_day', $isEdit ? $attendance->attendance_day->format('Y-m-d') : date('Y-m-d')) }}" required>
        </div>
        
        <div class="col-md-6 mb-3">
            <label for="attendance_type" class="form-label">Type <span class="text-danger">*</span></label>
            <select name="attendance_type" id="attendance_type" class="form-select" required>
                <option value="member" {{ old('attendance_type', $isEdit ? $attendance->attendance_type : '') == 'member' ? 'selected' : '' }}>Member</option>
                <option value="staff" {{ old('attendance_type', $isEdit ? $attendance->attendance_type : '') == 'staff' ? 'selected' : '' }}>Staff</option>
            </select>
        </div>
    </div>

    <div class="mb-3">
        <label for="user_id" class="form-label">User <span class="text-danger">*</span></label>
        <select name="user_id" id="user_id" class="form-select select2" required>
            <option value="">Select User</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ old('user_id', $isEdit ? $attendance->user_id : '') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="organization_id" class="form-label">Organization <span class="text-danger">*</span></label>
            <select name="organization_id" id="organization_id" class="form-select" required>
                @foreach($organizations as $org)
                    <option value="{{ $org->id }}" {{ old('organization_id', $isEdit ? $attendance->organization_id : '') == $org->id ? 'selected' : '' }}>
                        {{ $org->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="branch_id" class="form-label">Branch <span class="text-danger">*</span></label>
            <select name="branch_id" id="branch_id" class="form-select" required>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" {{ old('branch_id', $isEdit ? $attendance->branch_id : '') == $branch->id ? 'selected' : '' }}>
                        {{ $branch->name['en'] ?? $branch->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="check_in_at" class="form-label">Check In Time</label>
            <input type="datetime-local" name="check_in_at" id="check_in_at" class="form-control"
                   value="{{ old('check_in_at', $isEdit && $attendance->check_in_at ? $attendance->check_in_at->format('Y-m-d\TH:i') : '') }}">
        </div>
        <div class="col-md-6 mb-3">
            <label for="check_out_at" class="form-label">Check Out Time</label>
            <input type="datetime-local" name="check_out_at" id="check_out_at" class="form-control"
                   value="{{ old('check_out_at', $isEdit && $attendance->check_out_at ? $attendance->check_out_at->format('Y-m-d\TH:i') : '') }}">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
            <select name="status" id="status" class="form-select" required>
                <option value="present" {{ old('status', $isEdit ? $attendance->status : 'present') == 'present' ? 'selected' : '' }}>Present</option>
                <option value="absent" {{ old('status', $isEdit ? $attendance->status : '') == 'absent' ? 'selected' : '' }}>Absent</option>
                <option value="half_day" {{ old('status', $isEdit ? $attendance->status : '') == 'half_day' ? 'selected' : '' }}>Half Day</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="source" class="form-label">Source <span class="text-danger">*</span></label>
            <select name="source" id="source" class="form-select" required>
                <option value="manual" {{ old('source', $isEdit ? $attendance->source : 'manual') == 'manual' ? 'selected' : '' }}>Manual</option>
                <option value="qr" {{ old('source', $isEdit ? $attendance->source : '') == 'qr' ? 'selected' : '' }}>QR Code</option>
                <option value="biometric" {{ old('source', $isEdit ? $attendance->source : '') == 'biometric' ? 'selected' : '' }}>Biometric</option>
                <option value="app" {{ old('source', $isEdit ? $attendance->source : '') == 'app' ? 'selected' : '' }}>Mobile App</option>
            </select>
        </div>
    </div>

     <div class="mb-3 form-check">
        <input type="hidden" name="is_billable" value="0">
        <input type="checkbox" class="form-check-input" id="is_billable" name="is_billable" value="1" 
               {{ old('is_billable', $isEdit ? $attendance->is_billable : true) ? 'checked' : '' }}>
        <label class="form-check-label" for="is_billable">Is Billable Session?</label>
    </div>

    <div class="text-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="save-btn">
            {{ $isEdit ? 'Update' : 'Record' }} Attendance
        </button>
    </div>
</form>
