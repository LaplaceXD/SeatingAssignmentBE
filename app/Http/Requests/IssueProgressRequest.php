<?php

namespace App\Http\Requests;

use App\Enums\IssueStatus;
use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IssueProgressRequest extends FormRequest
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
            'AssigneeID' => [
                'required_without_all:Status',
                'filled', 'nullable', 'numeric',
                Rule::exists('Users', 'UserID')->where('Role', UserRole::Technician->value)
            ],
            'Status' => [
                'required_without_all:AssigneeID',
                'filled',
                Rule::in(array_map(function (IssueStatus $status) {
                    return $status->value;
                }, IssueStatus::postValidatedCases()))
            ]
        ];
    }

    public function attributes(): array
    {
        return [
            'AssigneeID' => 'assignee ID',
            'Status' => 'status'
        ];
    }
}
