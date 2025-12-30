<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBranchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'organization_id' => 'required|exists:organizations,id',
            'parent_id'       => 'nullable|exists:branches,id',
            'name'            => 'required|array',
            'name.*'          => 'required|string',
            'address'         => 'required|array',
            'address.*'       => 'required|string',
            'status'          => 'required|string|in:active,inactive',
        ];
    }
}
