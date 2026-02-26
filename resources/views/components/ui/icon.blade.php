@props([
'name',
'color' => null, // Default null agar tidak muncul background
'type' => 'o',
'size' => 'md', // xs, sm, md, lg, xl
])

@php
$colors = [
'blue' => 'bg-blue-50 text-blue-600 border-blue-200
hover:border-blue-500 hover:bg-blue-100
dark:bg-blue-900/20 dark:text-blue-300 dark:border-blue-800
dark:hover:border-blue-400 dark:hover:bg-blue-900/40',

'green' => 'bg-green-50 text-green-600 border-green-200
hover:border-green-500 hover:bg-green-100
dark:bg-green-900/20 dark:text-green-300 dark:border-green-800
dark:hover:border-green-400 dark:hover:bg-green-900/40',

'yellow' => 'bg-yellow-50 text-yellow-600 border-yellow-200
hover:border-yellow-500 hover:bg-yellow-100
dark:bg-yellow-900/20 dark:text-yellow-300 dark:border-yellow-800
dark:hover:border-yellow-400 dark:hover:bg-yellow-900/40',

'red' => 'bg-red-50 text-red-600 border-red-200
hover:border-red-500 hover:bg-red-100
dark:bg-red-900/20 dark:text-red-300 dark:border-red-800
dark:hover:border-red-400 dark:hover:bg-red-900/40',

'gray' => 'bg-gray-50 text-gray-600 border-gray-200
hover:border-gray-500 hover:bg-gray-100
dark:bg-gray-800/40 dark:text-gray-300 dark:border-gray-700
dark:hover:border-gray-400 dark:hover:bg-gray-800/60',
];

$sizes = [
'xs' => [
'box' => 'w-6 h-6',
'icon' => 'w-3 h-3',
'plain' => 'w-4 h-4'
],
'sm' => [
'box' => 'w-8 h-8',
'icon' => 'w-4 h-4',
'plain' => 'w-5 h-5'
],
'md' => [
'box' => 'w-9 h-9',
'icon' => 'w-5 h-5',
'plain' => 'w-6 h-6'
],
'lg' => [
'box' => 'w-11 h-11',
'icon' => 'w-6 h-6',
'plain' => 'w-7 h-7'
],
'xl' => [
'box' => 'w-12 h-12',
'icon' => 'w-7 h-7',
'plain' => 'w-8 h-8'
],
];

$currentSize = $sizes[$size] ?? $sizes['md'];

$iconMap = [
'info' => 'information-circle',
'edit' => 'pencil-square',
'delete' => 'trash',
'send' => 'paper-airplane',
'home' => 'home',
'bank' => 'building-library',
'mitra' => 'academic-cap',
'x' => 'x-mark',
'check' => 'check',
'eye' => 'eye',
'archive' => 'archive-box',
'plus' => 'plus',,
// TAMBAHAN: Icon untuk card stats
'building' => 'building-office', // Fix: building -> building-office
'clipboard' => 'clipboard-document', // Fix: clipboard -> clipboard-document
'check-circle' => 'check-circle',
'document' => 'document-text',
'users' => 'users',
];

$heroIconName = $iconMap[$name] ?? $name;
$componentName = "heroicon-{$type}-{$heroIconName}";
@endphp


@if($color)
{{-- Tampilan dengan KOTAK --}}
<span {{ $attributes->merge([
    'class' => "inline-flex items-center justify-center rounded-lg border transition "
        . $currentSize['box'] . ' '
        . ($colors[$color] ?? $colors['gray'])
]) }}>
    <x-dynamic-component
        :component="$componentName"
        class="{{ $currentSize['icon'] }}" />
</span>
@else
{{-- Tampilan POLOS --}}
<x-dynamic-component
    :component="$componentName"
    {{ $attributes->merge([
        'class' => $currentSize['plain']
    ]) }} />
@endif