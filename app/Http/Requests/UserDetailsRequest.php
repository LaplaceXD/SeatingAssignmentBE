<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserDetailsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'FirstName' => ['required', 'string', 'alpha_dash:ascii', 'between:2,256'],
            'LastName' => ['required', 'string', 'alpha_dash:ascii', 'between:2,256'],
            'Email' => [
                'required', 'email', Rule::unique('Users', 'Email')
                    ->ignore($this->route('user'), 'UserID')
                    ->where('Email', $this->Email)
            ],
        ];
    }
}
