<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetUserLDAP extends FormRequest
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
            'cpf' => 'required|numeric|digits:11',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */

    public function messages(): array

    {
        return [
            'cpf.required' => 'O campo CPF é obrigatório.',
            'cpf.numeric' => 'O campo CPF deve conter apenas números.',
            'cpf.digits' => 'O campo CPF deve conter 11 dígitos.',
        ];
    }
}
