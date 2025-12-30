<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'organization_id' => 'required|exists:organizations,id',
            'branch_id'       => 'required|exists:branches,id',
            'user_id'         => 'required|exists:users,id',
            'attendance_type' => 'required|string|in:member,staff',
            'attendance_day'  => 'required|date',
            'check_in_at'     => 'nullable|date',
            'check_out_at'    => 'nullable|date|after_or_equal:check_in_at',
            'source'          => 'required|string|in:manual,qr,biometric,app',
            'status'          => 'required|string|in:present,absent,half_day',
            'is_billable'     => 'boolean',
        ];
    }
}
