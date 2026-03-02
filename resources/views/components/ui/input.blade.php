@props([
    'name' => null,
    'label' => null,
    'type' => 'text',
    'placeholder' => '',
    'options' => [],
    'multiple' => false
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
        $model = $attributes->wire('model')->value();

        $baseClass = "
            w-full
            bg-white dark:bg-slate-900
            text-slate-800 dark:text-slate-100
            placeholder-slate-400 dark:placeholder-slate-500
            border
            focus:ring-1 dark:focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500
            focus:border-blue-600 dark:focus:border-blue-500
        ";

        $borderClass = $model && $errors->has($model)
            ? 'border-red-500'
            : 'border-slate-300 dark:border-slate-700';
    @endphp

    {{-- TEXTAREA --}}
    @if($type === 'textarea')
        <textarea
            id="{{ $name }}"
            placeholder="{{ $placeholder }}"
            {{ $attributes->merge([
                'class' => "textarea textarea-bordered h-24 $baseClass $borderClass"
            ]) }}></textarea>

    {{-- SELECT --}}
    @elseif($type === 'select')
        <select
            id="{{ $name }}"
            @if($multiple) multiple @endif
            {{ $attributes->merge([
                'class' => "select select-bordered $baseClass $borderClass " . ($multiple ? 'h-auto' : '')
            ]) }}>

            @if($placeholder && !$multiple)
                <option value="">{{ $placeholder }}</option>
            @endif

            @foreach($options as $value => $text)
                <option value="{{ $value }}">
                    {{ $text }}
                </option>
            @endforeach

        </select>

    {{-- FILE --}}
    @elseif($type === 'file')
        <input
            id="{{ $name }}"
            type="file"
            {{ $attributes->merge([
                'class' => "
                    file-input
                    w-full
                    bg-white dark:bg-slate-900
                    text-slate-800 dark:text-slate-100
                    border $borderClass
                    file-input-bordered
                "
            ]) }} />

    {{-- DEFAULT INPUT --}}
    @else
        <input
            id="{{ $name }}"
            type="{{ $type }}"
            placeholder="{{ $placeholder }}"
            {{ $attributes->merge([
                'class' => "input input-bordered $baseClass $borderClass"
            ]) }}>
    @endif

    @error($model)
        <div class="text-red-500 text-xs mt-1">
            {{ $message }}
        </div>
    @enderror
</div>
