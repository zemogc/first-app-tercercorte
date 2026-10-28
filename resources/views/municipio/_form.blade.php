@php($municipio ??= null)

<x-geo.field name="muni_nomb" label="Nombre del municipio">
    <input type="text" id="muni_nomb" name="muni_nomb" class="geo-input" required maxlength="80"
           value="{{ old('muni_nomb', $municipio?->muni_nomb) }}" placeholder="Santiago de Cali">
</x-geo.field>

<x-geo.field name="depa_codi" label="Departamento">
    <select id="depa_codi" name="depa_codi" class="geo-input" required>
        <option value="" disabled @selected(! old('depa_codi', $municipio?->depa_codi))>Selecciona un departamento...</option>
        @foreach ($departamentos as $departamento)
            <option value="{{ $departamento->depa_codi }}" @selected(old('depa_codi', $municipio?->depa_codi) == $departamento->depa_codi)>
                {{ $departamento->depa_nomb }} · {{ $departamento->pais?->pais_nomb }}
            </option>
        @endforeach
    </select>
</x-geo.field>

<x-geo.field name="muni_pobl" label="Población" hint="Opcional. Número aproximado de habitantes.">
    <input type="number" id="muni_pobl" name="muni_pobl" class="geo-input" min="0" step="1"
           value="{{ old('muni_pobl', $municipio?->muni_pobl) }}" placeholder="2280000">
</x-geo.field>
