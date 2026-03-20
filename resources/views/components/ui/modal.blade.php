@props([
'open' => false,
'maxWidth' => 'max-w-lg'
])

@if($open)
@teleport('body')
<div class="fixed inset-0 z-[999] flex items-center justify-center p-4">

    {{-- Backdrop dengan Animasi Fade --}}
    <div
        class="absolute inset-0 bg-black/50 dark:bg-black/80 backdrop-blur-sm transition-opacity duration-3000 ease-out"
        style="animation: fadeIn 2s ease-out;"
        {{ $attributes->whereStartsWith('wire:click') }}>
    </div>

    {{-- Modal Box dengan Animasi Zoom/Fade --}}
    <div class="relative w-full {{ $maxWidth }} bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl shadow-2xl z-[1000] overflow-hidden"
        style="animation: modalZoom 0.2s ease-out;">
        {{ $slot }}
    </div>
</div>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes modalZoom {
        from {
            opacity: 0;
            transform: scale(0.95);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>
@endteleport
@endif