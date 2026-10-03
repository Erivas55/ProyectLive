<x-field name="telefono" label="Teléfono (opcional)" :value="$perfil->telefono ?? ''" />
<x-field name="profesion" label="Profesión" :value="$perfil->profesion ?? ''" required />
<x-field name="biografia" label="Biografía (opcional)" type="textarea" :value="$perfil->biografia ?? ''" />
<div class="flex items-center justify-end gap-3 border-t border-zinc-200 pt-5 dark:border-zinc-700"><button type="submit" class="inline-flex items-center justify-center rounded-lg bg-zinc-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-700 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">Guardar perfil</button></div>
