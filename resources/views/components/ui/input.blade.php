@props([
'label' => null,
'type' => 'text',
])

<div class="form-control w-full">
    @if ($label)
    <label class="label">
        <span class="label-text">{{ $label }}</span>
    </label>
    @endif

    <input
        type="{{ $type }}"
        {{ $attributes->merge([
            'class' => 'input input-bordered w-full'
        ]) }} />
</div>