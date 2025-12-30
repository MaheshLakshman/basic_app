<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'service_id' => 'required|exists:services,id',
            'name' => 'required|array',
            'name.*' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration_type' => 'required|string|in:day,month,year',
            'duration_value' => 'required|integer|min:1',
            'usage_limit' => 'nullable|integer|min:1',
        ];
    }
}
