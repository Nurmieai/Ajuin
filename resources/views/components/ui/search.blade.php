@props(['placeholder' => 'Cari sesuatu...'])

<div {{ $attributes->merge(['class' => 'flex w-full md:w-1/3']) }}>
    <input
        type="text"
        wire:model.live.debounce.300ms="search"
        placeholder="{{ $placeholder }}"

        class="w-full px-4 py-2
               rounded-l-lg
               text-sm

               bg-white dark:bg-slate-900
               text-slate-700 dark:text-slate-200
               placeholder-slate-400 dark:placeholder-slate-500

               border border-slate-300 dark:border-slate-700
               focus:outline-none
               focus:ring-2 focus:ring-blue-500/40
               focus:border-blue-500 dark:focus:border-blue-400

               transition" />

    <button
        type="button"
        class="px-4
               rounded-r-lg

               bg-blue-600 dark:bg-blue-500
               text-white

               hover:bg-blue-500 dark:hover:bg-blue-400
               active:scale-95

               transition
               flex items-center justify-center">

        <svg xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
    </button>
</div>