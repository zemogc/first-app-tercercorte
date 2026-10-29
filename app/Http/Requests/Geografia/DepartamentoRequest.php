<?php

namespace App\Http\Requests\Geografia;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepartamentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'depa_nomb' => ['required', 'string', 'max:80'],
            'pais_codi' => ['required', 'string', Rule::exists('tb_pais', 'pais_codi')],
        ];
    }

    public function attributes(): array
    {
        return [
            'depa_nomb' => 'nombre del departamento',
            'pais_codi' => 'país',
        ];
    }
}
