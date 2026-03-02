@props([
    'label' => '',
    'options' => []
])

<div class="w-full">
    @if($label)
        <label class="block text-sm font-medium mb-1">
            {{ $label }}
        </label>
    @endif

    <select
        {{ $attributes->merge([
            'class' => '
                w-full rounded-md border px-3 py-2 text-sm
                bg-white text-slate-700 border-slate-300
                focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
            '
        ]) }}
    >
        <option value="">-- Pilih --</option>

        @foreach($options as $value => $text)
            <option value="{{ $value }}">
                {{ $text }}
            </option>
        @endforeach
    </select>
</div>
