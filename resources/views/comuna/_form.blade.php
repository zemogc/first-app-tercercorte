@php($comuna ??= null)

<x-geo.field name="comu_nomb" label="Nombre de la comuna">
    <input type="text" id="comu_nomb" name="comu_nomb" class="geo-input" required maxlength="80"
           value="{{ old('comu_nomb', $comuna?->comu_nomb) }}" placeholder="Comuna 2">
</x-geo.field>

<x-geo.field name="muni_codi" label="Municipio">
    <select id="muni_codi" name="muni_codi" class="geo-input" required>
        <option value="" disabled @selected(! old('muni_codi', $comuna?->muni_codi))>Selecciona un municipio...</option>
        @foreach ($municipios as $municipio)
            <option value="{{ $municipio->muni_codi }}" @selected(old('muni_codi', $comuna?->muni_codi) == $municipio->muni_codi)>
                {{ $municipio->muni_nomb }}
            </option>
        @endforeach
    </select>
</x-geo.field>
