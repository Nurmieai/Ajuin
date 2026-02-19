@props([
'name',
'label' => null,
'type' => 'text',
'placeholder' => ''
])

<div class="w-full">
    @if($label)
    <label class="label py-1" for="{{ $name }}">
        <span class="label-text font-semibold text-slate-600 dark:text-slate-300">
            {{ $label }}
        </span>
    </label>
    @endif

    @php
    $baseClass = "
    w-full
    bg-white dark:bg-slate-900
    text-slate-800 dark:text-slate-100
    placeholder-slate-400 dark:placeholder-slate-500
    border
    focus:ring-1 dark:focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500
    focus:border-blue-600 dark:focus:border-blue-500
    file:mr-4 file:py-2 file:px-4
    file:rounded-l-md file:border-0
    file:text-sm file:font-semibold
    file:bg-blue-50 file:text-blue-700
    hover:file:bg-blue-100 dark:hover:file:bg-blue-500
    dark:file:bg-slate-800 dark:file:text-slate-200
    ";

    $borderClass = $errors->has($name)
    ? 'border-red-500'
    : 'border-slate-300 dark:border-slate-700';
    @endphp

    {{-- TEXTAREA --}}
    @if($type === 'textarea')
    <textarea
        id="{{ $name }}"
        wire:model="{{ $name }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['class' => "textarea textarea-bordered h-24 $baseClass $borderClass"]) }}>
        </textarea>

    @elseif($type === 'file')
    <fieldset class="fieldset">
        <legend class="fieldset-legend">{{ $label ?? 'Upload File' }}</legend>

        <input
            id="{{ $name }}"
            type="file"
            wire:model="{{ $name }}"
            {{ $attributes->merge(['class' => "file-input file-input-bordered w-full $baseClass $borderClass"]) }} />

        <label class="label">
            <span class="label-text-alt">Max size 2MB</span>
        </label>
    </fieldset>

    @else
    <input
        id="{{ $name }}"
        type="{{ $type }}"
        wire:model="{{ $name }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['class' => "input input-bordered $baseClass $borderClass"]) }}>
    @endif

    @error($name)
    <div class="text-red-500 text-xs mt-1 flex items-center gap-1">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        {{ $message }}
    </div>
    @enderror
</div>