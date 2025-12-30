<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubscriptionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'plan_id' => 'required|exists:plans,id',
            'start_date' => 'required|date',
            // End date can be auto-calculated or manually overridden, let's allow override
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|string|in:active,frozen,expired',
        ];
    }
}
