<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends FormRequest
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
            'description' => 'nullable|array',
            'description.*' => 'nullable|string',
            'service_type' => 'required|string|in:gym,pt,class',
            'billing_type' => 'required|string|in:subscription,usage,hybrid',
        ];
    }
}
