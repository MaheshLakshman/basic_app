<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLanguageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('language')->id;
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:languages,code,' . $id,
            'is_active' => 'required|boolean',
        ];
    }
}
