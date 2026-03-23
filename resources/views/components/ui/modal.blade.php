@props([
'open' => false,
'maxWidth' => 'max-w-lg'
])

{{-- Tidak perlu teleport, biarkan Livewire memantau perubahannya secara langsung --}}
<div x-data="{ show: @js($open) }"
    x-init="$watch('show', value => { if(!value) { setTimeout(() => { $dispatch('modal-closed') }, 300) } })"
    x-effect="show = @js($open)"
    x-show="show"
    style="display: none;"
    class="fixed inset-0 z-[9998] flex items-center justify-center p-4">

    {{-- Backdrop --}}
    <div x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-black/50 dark:bg-black/40 backdrop-blur-sm"
        {{ $attributes->whereStartsWith('wire:click') }}>
    </div>

    {{-- Modal Box --}}
    <div x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        class="relative w-full {{ $maxWidth }} bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl z-[1000] flex flex-col max-h-[90vh]">

        <div class="overflow-y-auto">
            {{ $slot }}
        </div>

    </div>
</div>