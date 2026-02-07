@props(['placeholder' => 'Cari sesuatu...'])

<div {{ $attributes->merge(['class' => 'flex w-1/3']) }}>
    <input
        type="text"
        {{-- wire:model.live agar pencarian langsung berubah saat mengetik --}}
        wire:model.live.debounce.300ms="search"
        class="text-slate-700 w-full px-4 py-1 rounded-s-lg focus:outline-none shadow-[inset_0_4px_6px_rgba(0,0,0,0.1)] border border-slate-300/50"
        placeholder="{{ $placeholder }}">

    <button class="bg-green-500 hover:bg-green-600 text-white font-bold px-4 rounded-r-lg transition">
        <svg xmlns="http://www.w3.org" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
    </button>
</div>