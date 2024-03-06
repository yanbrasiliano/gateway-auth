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
            'password' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'cpf.required' => 'O campo CPF é obrigatório.',
            'cpf.string' => 'O campo CPF deve ser uma string.',
            'password.required' => 'O campo senha é obrigatório.',
        ];
    }
}
