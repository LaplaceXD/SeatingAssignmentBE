<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
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
            'FirstName' => ['required', 'string', 'between:2,256'],
            'LastName' => ['required', 'string', 'between:2,256'],
            'Email' => [
                'required', 'email', Rule::unique('users', 'Email')
                    ->ignore($this->route('user'), 'UserID')
                    ->where(function ($query) {
                        $query->where('Email', $this->Email);
                    })
            ],
        ];
    }
}
