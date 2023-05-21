<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IssueDetailsRequest extends FormRequest
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
            'LabID' => ['required', 'numeric', 'exists:Laboratories,LabID'],
            'TypeID' => ['nullable', 'numeric', 'exists:IssueTypes,TypeID'],
            'SeatNo' => ['required', 'string', 'alpha_num:ascii', 'between:1,4'],
            'Description' => ['string', 'max:1024'],
            'ReplicationSteps' => ['required', 'string'],
        ];
    }

    public function attributes()
    {
        return [
            'IssuerID' => 'issuer ID',
            'LabID' => 'laboratory ID',
            'TypeID' => 'issue type ID',
            'SeatNo' => 'seat number',
            'ReplicationSteps' => 'replication steps',
        ];
    }
}
