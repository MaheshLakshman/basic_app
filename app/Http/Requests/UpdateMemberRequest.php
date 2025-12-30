<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMemberRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $userId = $this->route('member')->id; // 'member' route parameter is actually a User model instance
        return [
            'organization_id' => 'required|exists:organizations,id',
            'name' => 'required|array',
            'name.*' => 'required|string',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'password' => 'nullable|string|min:8',
            
            // Profile fields
            'member_code' => ['nullable', 'string', Rule::unique('member_profiles', 'member_code')->ignore($userId, 'user_id')],
            'join_date' => 'nullable|date',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,other',
            'emergency_contact' => 'nullable|string',
        ];
    }
}
