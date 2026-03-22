@props([
'open' => false,
'title' => 'Konfirmasi',
'message' => 'Apakah kamu yakin?',
'confirmText' => 'Ya',
'cancelText' => 'Batal',
'confirmAction' => null,
'type' => 'danger', // Pilihan: danger, success, primary
])

@teleport('body')
<div x-data="{ show: false }"
    x-effect="show = @js($open)"
    x-show="show"
    style="display: none;"
    class="fixed inset-0 z-[10000] flex items-center justify-center p-4">

    {{-- Backdrop (Diperbaiki: Dihapus border dan rounded-nya) --}}
    <div x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-black/40 dark:bg-black/40 backdrop-blur-sm"
        wire:click="$set('confirmingAction', null)">
    </div>

    {{-- Kotak Modal Konfirmasi --}}
    <div x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        class="bg-white dark:bg-slate-950 rounded-xl shadow-2xl w-full max-w-md relative z-10 
               border border-slate-200 dark:border-slate-800 overflow-hidden">

        {{-- Area Konten Utama --}}
        <div class="p-6">
            <div class="flex items-start gap-4">

                {{-- Ikon Dinamis berdasarkan Type --}}
                <div class="shrink-0 flex items-center justify-center size-12 rounded-full
                    @if($type === 'danger') bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-500
                    @elseif($type === 'success') bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-500
                    @else bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-500 @endif">

                    @if($type === 'danger')
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    @elseif($type === 'success')
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    @else
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    @endif
                </div>

                {{-- Teks --}}
                <div class="pt-1">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">
                        {{ $title }}
                    </h3>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                        {{ $message }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Area Footer (Tombol Aksi) --}}
        <div class="bg-slate-50 dark:bg-slate-900/50 px-6 py-4 flex justify-end gap-3 border-t border-slate-200 dark:border-slate-800">
            <button
                wire:click="$set('confirmingAction', null)"
                class="btn btn-ghost text-slate-700 dark:text-slate-300">
                {{ $cancelText }}
            </button>

            <button
                wire:click="{{ $confirmAction }}"
                class="btn border-none text-white
                @if($type === 'danger') bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600
                @elseif($type === 'success') bg-green-600 hover:bg-green-700 dark:bg-green-600 dark:hover:bg-green-700
                @else bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 @endif">
                {{ $confirmText }}
            </button>
        </div>

    </div>
</div>
@endteleport