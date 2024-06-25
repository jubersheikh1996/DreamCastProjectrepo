<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|digits:10',
            'role_id' => 'required|integer|exists:roles,id',
            'description' => 'required|string|max:500',
            'file' => 'required|mimes:jpg,jpeg,png',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'phone.required' => 'The phone field is required.',
            'role_id.required' => 'The role field is required.',
            'description.required' => 'The description field is required.',
            'file.mimes' => 'The profile image must be a file of type: jpg, jpeg, png,',
            'file.max' => 'The profile image may not be greater than 2MB.',
        ];
    }
}
