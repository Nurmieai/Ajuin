@props(['placeholder' => 'Cari sesuatu...'])

<label {{ $attributes->merge(['class' => '
    tooltip
    flex items-center gap-2
    w-full sm:max-w-1/3
    px-4 py-2
    rounded-lg
    text-sm

    bg-white dark:bg-slate-900
    text-slate-700 dark:text-slate-200

    border border-slate-300 dark:border-slate-700
    focus-within:ring-2 focus-within:ring-blue-500/40
    focus-within:border-blue-500 dark:focus-within:border-blue-400

    transition
']) }}
    data-tip="cari sesuatu...">

    {{-- Search icon --}}
    <svg xmlns="http://www.w3.org/2000/svg"
        class="h-4 w-4 opacity-50 shrink-0"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor">
        <g stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2.5">
            <circle cx="11" cy="11" r="8"></circle>
            <path d="m21 21-4.3-4.3"></path>
        </g>
    </svg>

    {{-- Input --}}
    <input
        type="search"
        wire:model.live.debounce.300ms="search"
        placeholder="{{ $placeholder }}"
        required

        class="w-full bg-transparent
               focus:outline-none
               placeholder-slate-400 dark:placeholder-slate-500" />

</label>