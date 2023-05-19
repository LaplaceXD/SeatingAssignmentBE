<?php

namespace App\Http\Requests;

use App\Http\Requests\UserDetailsRequest;
use Illuminate\Validation\Rules\Password;

class ChangePasswordRequest extends UserDetailsRequest
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
            'OldPassword' => ['required', 'string'],
            'Password' => array_merge(ChangePasswordRequest::password_validation(), ['different:OldPassword']),
            'ConfirmPassword' => ['required', 'string', 'same:Password']
        ];
    }

    public static function password_validation(): array
    {
        return ['required', 'string', 'max:15', Password::min(8)->letters()->mixedCase()->numbers()->symbols()];
    }
}
