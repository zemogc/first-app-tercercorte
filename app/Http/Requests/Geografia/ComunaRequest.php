<?php

namespace App\Http\Requests\Geografia;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ComunaRequest extends FormRequest
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
            'comu_nomb' => ['required', 'string', 'max:80'],
            'muni_codi' => ['required', 'integer', Rule::exists('tb_municipio', 'muni_codi')],
        ];
    }

    public function attributes(): array
    {
        return [
            'comu_nomb' => 'nombre de la comuna',
            'muni_codi' => 'municipio',
        ];
    }
}
