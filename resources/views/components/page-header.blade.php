@props(['title', 'subtitle' => null])

<div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <h1 class="text-2xl font-semibold text-zinc-900 dark:text-white">{{ $title }}</h1>
        @if ($subtitle)
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">{{ $subtitle }}</p>
        @endif
    </div>
    <div class="flex gap-2">{{ $slot }}</div>
</div>
