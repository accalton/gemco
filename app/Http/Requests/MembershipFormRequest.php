<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MembershipFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function messages()
    {
        return [
            'type' => 'Please select the membership type.',

            'members.*.date_of_birth' => 'Date of birth is required.',
            'members.*.email' => 'Email address is required.',
            'members.*.name'  => 'Name is required.',
            'members.*.phone' => 'Phone number is required.',

            'contacts.*.name'  => 'Name is required.',
            'contacts.*.phone' => 'Phone number is required.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => 'required',

            'members.*.date_of_birth' => 'required',
            'members.*.email' => 'required|email',
            'members.*.name' => 'required',
            'members.*.phone' => 'required',

            'contacts.*.name' => 'required',
            'contacts.*.phone' => 'required',
        ];
    }
}
