@props([
'title',
'value',
'icon',
'color' => 'blue'
])

@php
$colors = [
'green' => 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400',
'blue' => 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400',
'purple' => 'bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400',
'indigo' => 'bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400',
'orange' => 'bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400',
][$color];
@endphp

<div class="flex flex-col p-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 theme-transition">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-slate-500 dark:text-slate-400">{{ $title }}</p>
            <h3 class="text-2xl font-bold text-slate-700 dark:text-slate-200">{{ $value }}</h3>
        </div>
        <div class="p-3 rounded-full {{ $colors }}">
            {{-- Ganti dari x-ui.icon ke svg langsung atau pakai icon yang ada --}}
            <x-ui.icon :name="$icon" class="w-6 h-6" />
        </div>
    </div>
</div>