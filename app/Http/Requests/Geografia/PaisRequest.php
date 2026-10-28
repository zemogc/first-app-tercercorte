<?php

namespace App\Http\Requests\Geografia;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaisRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('pais_codi')) {
            $this->merge(['pais_codi' => strtoupper(trim($this->input('pais_codi')))]);
        }
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $pais = $this->route('pais');
        $codigo = is_object($pais) ? $pais->pais_codi : $pais;

        return [
            'pais_codi' => $codigo
                ? ['prohibited']
                : ['required', 'alpha', 'size:3', Rule::unique('tb_pais', 'pais_codi')],
            'pais_nomb' => ['required', 'string', 'max:80', Rule::unique('tb_pais', 'pais_nomb')->ignore($codigo, 'pais_codi')],
            'pais_capi' => ['nullable', 'integer', Rule::exists('tb_municipio', 'muni_codi')],
        ];
    }

    public function attributes(): array
    {
        return [
            'pais_codi' => 'código',
            'pais_nomb' => 'nombre del país',
            'pais_capi' => 'capital',
        ];
    }
}
