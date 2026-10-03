@php
    $v = $vacante ?? null;
@endphp
@if (auth()->user()->isAdmin())
    <x-field name="companie_id" label="Empresa" type="select" required>
        <option value="">Selecciona una empresa</option>
        @foreach ($empresas as $empresa)
            <option value="{{ $empresa->id }}" @selected((int) old('companie_id', $v?->companie_id) === $empresa->id)>{{ $empresa->nombre }}</option>
        @endforeach
    </x-field>
@endif
<x-field name="titulo" label="Título" :value="$v?->titulo" required />
<x-field name="descripcion" label="Descripción (opcional)" type="textarea" :value="$v?->descripcion" />
<x-field name="ubicacion" label="Ubicación (opcional)" :value="$v?->ubicacion" />
<x-field name="modalidad" label="Modalidad" type="select" required>
    @foreach (['presencial' => 'Presencial', 'remoto' => 'Remoto', 'hibrido' => 'Híbrido'] as $k => $t)
        <option value="{{ $k }}" @selected(old('modalidad', $v?->modalidad) === $k)>{{ $t }}</option>
    @endforeach
</x-field>
<x-field name="tipo_contrato" label="Tipo de contrato" type="select" required>
    @foreach (['tiempo_completo' => 'Tiempo completo', 'medio_tiempo' => 'Medio tiempo', 'temporal' => 'Temporal', 'practica' => 'Práctica'] as $k => $t)
        <option value="{{ $k }}" @selected(old('tipo_contrato', $v?->tipo_contrato) === $k)>{{ $t }}</option>
    @endforeach
</x-field>
<x-field name="salario" label="Salario (opcional)" type="number" :value="$v?->salario" />
<x-field name="estado" label="Estado" type="select" required>
    @foreach (['activo' => 'Activo', 'pausado' => 'Pausado', 'cerrado' => 'Cerrado'] as $k => $t)
        <option value="{{ $k }}" @selected(old('estado', $v?->estado ?? 'activo') === $k)>{{ $t }}</option>
    @endforeach
</x-field>
<div class="flex items-center justify-end gap-3 border-t border-zinc-200 pt-5 dark:border-zinc-700">
    <a href="{{ route('vacantes.index') }}" class="font-medium text-zinc-700 hover:text-zinc-950 dark:text-zinc-300 dark:hover:text-white">Cancelar</a>
    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-zinc-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-700 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">Guardar</button>
</div>
