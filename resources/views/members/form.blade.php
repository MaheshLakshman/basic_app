@php
    $isEdit = isset($member);
    $actionUrl = $isEdit ? route('members.update', $member->id) : route('members.store');
@endphp

<form id="member-form" action="{{ $actionUrl }}" method="POST">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label for="organization_id" class="form-label">Organization <span class="text-danger">*</span></label>
            <select name="organization_id" id="organization_id" class="form-select @error('organization_id') is-invalid @enderror" required>
                <option value="" disabled {{ !$isEdit ? 'selected' : '' }}>Select Organization</option>
                @foreach($organizations as $org)
                    <option value="{{ $org->id }}" {{ old('organization_id', $isEdit ? $member->organization_id : '') == $org->id ? 'selected' : '' }}>
                        {{ $org->name }}
                    </option>
                @endforeach
            </select>
            @error('organization_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label for="member_code" class="form-label">Member Code</label>
            <input type="text" name="member_code" id="member_code" class="form-control"
                   value="{{ old('member_code', $isEdit && $member->memberProfile ? $member->memberProfile->member_code : '') }}" placeholder="Auto or custom">
        </div>
    </div>

    @foreach($languages as $language)
        <div class="mb-3">
            <label for="name_{{ $language->code }}" class="form-label">Name ({{ $language->name }}) <span class="text-danger">*</span></label>
            <input type="text" name="name[{{ $language->code }}]" id="name_{{ $language->code }}" 
                   class="form-control" 
                   value="{{ old('name.' . $language->code, $isEdit ? $member->getTranslation('name', $language->code, false) : '') }}" required>
        </div>
    @endforeach

    <div class="mb-3">
        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
        <input type="email" name="email" id="email" class="form-control" 
               value="{{ old('email', $isEdit ? $member->email : '') }}" required>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label for="password" class="form-label">Password {{ $isEdit ? '(Leave blank to keep)' : '' }}</label>
            <input type="password" name="password" id="password" class="form-control" 
                   {{ $isEdit ? '' : 'required' }}>
        </div>
        <div class="col-md-6">
            <label for="gender" class="form-label">Gender</label>
            <select name="gender" id="gender" class="form-select">
                <option value="" selected>Select Gender</option>
                <option value="male" {{ old('gender', $isEdit && $member->memberProfile ? $member->memberProfile->gender : '') == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('gender', $isEdit && $member->memberProfile ? $member->memberProfile->gender : '') == 'female' ? 'selected' : '' }}>Female</option>
                <option value="other" {{ old('gender', $isEdit && $member->memberProfile ? $member->memberProfile->gender : '') == 'other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label for="join_date" class="form-label">Join Date</label>
            <input type="date" name="join_date" id="join_date" class="form-control"
                   value="{{ old('join_date', $isEdit && $member->memberProfile && $member->memberProfile->join_date ? $member->memberProfile->join_date->format('Y-m-d') : date('Y-m-d')) }}">
        </div>
        <div class="col-md-6">
            <label for="dob" class="form-label">Date of Birth</label>
            <input type="date" name="dob" id="dob" class="form-control"
                   value="{{ old('dob', $isEdit && $member->memberProfile && $member->memberProfile->dob ? $member->memberProfile->dob->format('Y-m-d') : '') }}">
        </div>
    </div>

    <div class="mb-3">
        <label for="emergency_contact" class="form-label">Emergency Contact Or Address</label>
        <input type="text" name="emergency_contact" id="emergency_contact" class="form-control"
               value="{{ old('emergency_contact', $isEdit && $member->memberProfile ? $member->memberProfile->emergency_contact : '') }}">
    </div>

    <div class="text-end mt-4">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="save-btn">
            {{ $isEdit ? 'Update' : 'Create' }} Member
        </button>
    </div>
</form>
