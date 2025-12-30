<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTrainerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $userId = $this->route('trainer')->id;
        return [
            'organization_id' => 'required|exists:organizations,id',
            'name' => 'required|array',
            'name.*' => 'required|string',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'password' => 'nullable|string|min:8',
            
            // Profile fields
            'specialization' => 'nullable|array',
            'specialization.*' => 'string',
            'experience_years' => 'nullable|integer|min:0',
        ];
    }
}
