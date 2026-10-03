<x-field name="nombre" label="Nombre" :value="$empresa->nombre ?? ''" required />
<x-field name="descripcion" label="Descripción (opcional)" type="textarea" :value="$empresa->descripcion ?? ''" />
<x-field name="sitio_web" label="Sitio web (opcional)" type="url" :value="$empresa->sitio_web ?? ''" />
<x-field name="ubicacion" label="Ubicación (opcional)" :value="$empresa->ubicacion ?? ''" />
<div class="flex items-center justify-end gap-3 border-t border-zinc-200 pt-5 dark:border-zinc-700">
    <a href="{{ route('empresas.index') }}" class="font-medium text-zinc-700 hover:text-zinc-950 dark:text-zinc-300 dark:hover:text-white">Cancelar</a>
    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-zinc-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-700 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">Guardar</button>
</div>
