<?php

namespace App\Http\Requests;

use App\Rules\ValidCPF;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cpf' => ['required', new ValidCPF()],
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'email.cpf' => 'The cpf field must be a valid CPF number.',
            'cpf.cpf' => 'The cpf field must be a valid CPF number.',
            'password.required' => 'The password field is required.',
        ];
    }
}
