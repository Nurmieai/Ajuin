@props([
'variant' => 'neutral', // default variant
'size' => 'md', // default size
])

@php
// Mapping warna berdasarkan variant
$variants = [
'neutral' => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
'primary' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
'success' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
'warning' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
'danger' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
'info' => 'bg-cyan-100 text-cyan-700 dark:bg-cyan-900/30 dark:text-cyan-400',
];

// Mapping ukuran
$sizes = [
'xs' => 'px-2 py-0.5 text-[10px]',
'sm' => 'px-2.5 py-0.5 text-xs',
'md' => 'px-3 py-1 text-sm',
];

$variantClass = $variants[$variant] ?? $variants['neutral'];
$sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center font-semibold rounded-full $variantClass $sizeClass theme-transition"]) }}>
    {{ $slot }}
</span>