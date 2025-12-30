<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTrainerRequest extends FormRequest
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
            'specialization' => 'nullable|array', // JSON
            'specialization.*' => 'string',
            'experience_years' => 'nullable|integer|min:0',
        ];
    }
}
