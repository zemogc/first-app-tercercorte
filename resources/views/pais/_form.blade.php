@php($pais ??= null)

<x-geo.field name="pais_codi" label="Código ISO (3 letras)" hint="{{ $pais ? 'El código no se puede modificar.' : 'Ejemplo: COL, ECU, PER.' }}">
    <input type="text" id="pais_codi" name="pais_codi" maxlength="3" class="geo-input uppercase"
           value="{{ old('pais_codi', $pais?->pais_codi) }}" @disabled($pais) @required(! $pais)>
</x-geo.field>

<x-geo.field name="pais_nomb" label="Nombre del país">
    <input type="text" id="pais_nomb" name="pais_nomb" class="geo-input" required maxlength="80"
           value="{{ old('pais_nomb', $pais?->pais_nomb) }}" placeholder="Colombia">
</x-geo.field>

<x-geo.field name="pais_capi" label="Capital" hint="Opcional. Debe ser un municipio ya registrado.">
    <select id="pais_capi" name="pais_capi" class="geo-input">
        <option value="">Sin capital asignada</option>
        @foreach ($municipios as $municipio)
            <option value="{{ $municipio->muni_codi }}" @selected(old('pais_capi', $pais?->pais_capi) == $municipio->muni_codi)>
                {{ $municipio->muni_nomb }}
            </option>
        @endforeach
    </select>
</x-geo.field>
