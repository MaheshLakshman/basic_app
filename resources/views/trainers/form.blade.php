@php
    $isEdit = isset($trainer);
    $actionUrl = $isEdit ? route('trainers.update', $trainer->id) : route('trainers.store');
@endphp

<form id="trainer-form" action="{{ $actionUrl }}" method="POST">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="mb-3">
        <label for="organization_id" class="form-label">Organization <span class="text-danger">*</span></label>
        <select name="organization_id" id="organization_id" class="form-select @error('organization_id') is-invalid @enderror" required>
            <option value="" disabled {{ !$isEdit ? 'selected' : '' }}>Select Organization</option>
            @foreach($organizations as $org)
                <option value="{{ $org->id }}" {{ old('organization_id', $isEdit ? $trainer->organization_id : '') == $org->id ? 'selected' : '' }}>
                    {{ $org->name }}
                </option>
            @endforeach
        </select>
        @error('organization_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    @foreach($languages as $language)
        <div class="mb-3">
            <label for="name_{{ $language->code }}" class="form-label">Name ({{ $language->name }}) <span class="text-danger">*</span></label>
            <input type="text" name="name[{{ $language->code }}]" id="name_{{ $language->code }}" 
                   class="form-control" 
                   value="{{ old('name.' . $language->code, $isEdit ? $trainer->getTranslation('name', $language->code, false) : '') }}" required>
        </div>
    @endforeach

    <div class="mb-3">
        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
        <input type="email" name="email" id="email" class="form-control" 
               value="{{ old('email', $isEdit ? $trainer->email : '') }}" required>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label for="password" class="form-label">Password {{ $isEdit ? '(Leave blank to keep)' : '' }}</label>
            <input type="password" name="password" id="password" class="form-control" 
                   {{ $isEdit ? '' : 'required' }}>
        </div>
        <div class="col-md-6">
             <label for="experience_years" class="form-label">Experience (Years)</label>
            <input type="number" name="experience_years" id="experience_years" class="form-control" 
                   value="{{ old('experience_years', $isEdit && $trainer->trainerProfile ? $trainer->trainerProfile->experience_years : '') }}">
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Specializations</label>
        <div id="specializations-wrapper">
            @php
                $specs = old('specialization', $isEdit && $trainer->trainerProfile && $trainer->trainerProfile->specialization ? $trainer->trainerProfile->specialization : ['']);
            @endphp
            @foreach($specs as $index => $spec)
                <div class="input-group mb-2 spec-entry">
                    <input type="text" name="specialization[]" class="form-control" value="{{ $spec }}" placeholder="e.g. Yoga, Weightlifting">
                    @if($index > 0)
                        <button type="button" class="btn btn-outline-danger remove-spec">X</button>
                    @else
                        <button type="button" class="btn btn-outline-success add-spec">+</button>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <div class="text-end mt-4">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="save-btn">
            {{ $isEdit ? 'Update' : 'Create' }} Trainer
        </button>
    </div>
</form>

<script>
    // Simple inline script for specialization dynamic fields
    $(document).off('click', '.add-spec').on('click', '.add-spec', function() {
        let entry = `
            <div class="input-group mb-2 spec-entry">
                <input type="text" name="specialization[]" class="form-control" placeholder="e.g. Yoga, Weightlifting">
                <button type="button" class="btn btn-outline-danger remove-spec">X</button>
            </div>
        `;
        $('#specializations-wrapper').append(entry);
    });

    $(document).on('click', '.remove-spec', function() {
        $(this).closest('.spec-entry').remove();
    });
</script>
