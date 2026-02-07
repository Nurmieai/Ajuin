@props(['name', 'label' => null, 'type' => 'text', 'placeholder' => ''])

<div class="w-full">
    @if($label)
    <label class="label py-1" for="{{ $name }}">
        <span class="label-text font-semibold text-gray-600">{{ $label }}</span>
    </label>
    @endif

    @if($type === 'textarea')
    <textarea
        id="{{ $name }}"
        wire:model="{{ $name }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['class' => "textarea textarea-bordered w-full focus:ring-2 focus:ring-blue-500 h-24 " . ($errors->has($name) ? 'border-red-500' : 'border-gray-300')]) }}></textarea>
    @else
    <input
        id="{{ $name }}"
        type="{{ $type }}"
        wire:model="{{ $name }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['class' => "input input-bordered w-full focus:ring-2 focus:ring-blue-500 " . ($errors->has($name) ? 'border-red-500' : 'border-gray-300')]) }}>
    @endif

    @error($name)
    <div class="text-red-500 text-xs mt-1 flex items-center gap-1">
        <svg xmlns="http://www.w3.org" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        {{ $message }}
    </div>
    @enderror
</div>