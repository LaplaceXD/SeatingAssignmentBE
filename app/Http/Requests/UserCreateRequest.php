<?php

namespace App\Http\Requests;

use App\Http\Requests\UserDetailsRequest;

class UserCreateRequest extends UserDetailsRequest
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
        return array_merge(parent::rules(), [
            'Password' => ChangePasswordRequest::password_validation(),
            'ConfirmPassword' => ['required', 'string', 'same:Password']
        ]);
    }
}
