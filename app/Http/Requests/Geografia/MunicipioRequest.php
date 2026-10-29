<?php

namespace App\Http\Requests\Geografia;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MunicipioRequest extends FormRequest
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
            'muni_nomb' => ['required', 'string', 'max:80'],
            'depa_codi' => ['required', 'integer', Rule::exists('tb_departamento', 'depa_codi')],
            'muni_pobl' => ['nullable', 'integer', 'min:0', 'max:50000000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'muni_nomb' => 'nombre del municipio',
            'depa_codi' => 'departamento',
            'muni_pobl' => 'población',
        ];
    }
}
