@php($departamento ??= null)

<x-geo.field name="depa_nomb" label="Nombre del departamento">
    <input type="text" id="depa_nomb" name="depa_nomb" class="geo-input" required maxlength="80"
           value="{{ old('depa_nomb', $departamento?->depa_nomb) }}" placeholder="Valle del Cauca">
</x-geo.field>

<x-geo.field name="pais_codi" label="País">
    <select id="pais_codi" name="pais_codi" class="geo-input" required>
        <option value="" disabled @selected(! old('pais_codi', $departamento?->pais_codi))>Selecciona un país...</option>
        @foreach ($paises as $pais)
            <option value="{{ $pais->pais_codi }}" @selected(old('pais_codi', $departamento?->pais_codi) === $pais->pais_codi)>
                {{ $pais->pais_nomb }} ({{ $pais->pais_codi }})
            </option>
        @endforeach
    </select>
</x-geo.field>
