<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMemberRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'organization_id' => 'required|exists:organizations,id',
            'name' => 'required|array',
            'name.*' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            
            // Profile fields
            'member_code' => 'nullable|string|unique:member_profiles,member_code',
            'join_date' => 'nullable|date',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,other',
            'emergency_contact' => 'nullable|string',
        ];
    }
}
