@props([
'open' => false,
'maxWidth' => 'max-w-lg'
])

@if($open)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4">

    {{-- backdrop --}}
    <div
        class="absolute inset-0 bg-black/50 dark:bg-black/70 backdrop-blur-sm"
        {{ $attributes->whereStartsWith('wire:click') }}>
    </div>

    {{-- modal --}}
    <div class="
        relative
        w-full {{ $maxWidth }}
        bg-white dark:bg-slate-950
        border border-slate-200 dark:border-slate-800
        rounded-xl
        shadow-2xl
        animate-in fade-in zoom-in duration-200
    ">
        {{ $slot }}
    </div>
</div>
@endif