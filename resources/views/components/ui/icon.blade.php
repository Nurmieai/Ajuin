@props([
'name',
'color' => null, // Default null agar tidak muncul background
'type' => 'o'
])

@php
$colors = [
'blue' => 'bg-blue-100 text-blue-600 dark:bg-blue-900/40 dark:text-blue-300',
'green' => 'bg-green-100 text-green-600 dark:bg-green-900/40 dark:text-green-300',
'yellow' => 'bg-yellow-100 text-yellow-600 dark:bg-yellow-900/40 dark:text-yellow-300',
'red' => 'bg-red-100 text-red-600 dark:bg-red-900/40 dark:text-red-300',
'gray' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
];

$iconMap = [
'info' => 'information-circle',
'edit' => 'pencil-square',
'delete' => 'trash',
'send' => 'paper-airplane',
'home' => 'home',
'bank' => 'building-library',
'mitra' => 'academic-cap',
// Tambahkan mapping lainnya di sini
];

$heroIconName = $iconMap[$name] ?? $name;
$componentName = "heroicon-{$type}-{$heroIconName}";
@endphp

@if($color)
{{-- Tampilan dengan KOTAK (untuk Tabel/Aksi) --}}
<span {{ $attributes->merge(['class' => "inline-flex items-center justify-center w-9 h-9 rounded-lg " . ($colors[$color] ?? $colors['gray'])]) }}>
    <x-dynamic-component :component="$componentName" class="w-5 h-5" />
</span>
@else
{{-- Tampilan POLOS (untuk Sidebar) --}}
<x-dynamic-component :component="$componentName" {{ $attributes->merge(['class' => 'w-6 h-6']) }} />
@endif