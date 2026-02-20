@if ($paginator->hasPages())
<div class="flex items-center">

    {{-- Tombol Paling Awal --}}
    @if ($paginator->onFirstPage())
    <button class="px-3 py-2 text-sm 
                   rounded-s-sm border border-slate-300 dark:border-slate-700 
                   bg-white dark:bg-slate-900 
                   text-slate-300 dark:text-slate-600 
                   cursor-not-allowed" disabled>««</button>
    @else
    <button wire:click="gotoPage(1)"
        class="px-3 py-2 text-sm 
               rounded-s-sm border border-slate-300 dark:border-slate-700 
               bg-white dark:bg-slate-900 
               text-slate-700 dark:text-slate-200 
               hover:border-blue-500 dark:hover:border-blue-400 
               transition">««</button>
    @endif


    {{-- Tombol Sebelumnya --}}
    @if ($paginator->onFirstPage())
    <button class="px-3 py-2 text-sm 
                   border border-slate-300 dark:border-slate-700 
                   bg-white dark:bg-slate-900 
                   text-slate-300 dark:text-slate-600 
                   cursor-not-allowed" disabled>«</button>
    @else
    <button wire:click="previousPage"
        class="px-3 py-2 text-sm 
               border border-slate-300 dark:border-slate-700 
               bg-white dark:bg-slate-900 
               text-slate-700 dark:text-slate-200 
               hover:border-blue-500 dark:hover:border-blue-400 
               transition">«</button>
    @endif


    @php
    $current = $paginator->currentPage();
    $last = $paginator->lastPage();

    // ===== DESKTOP LOGIC (10 page ala Google) =====
    $startDesktop = max($current - 5, 1);
    $endDesktop = $startDesktop + 9;

    if ($endDesktop > $last) {
    $endDesktop = $last;
    $startDesktop = max($last - 9, 1);
    }

    // ===== MOBILE LOGIC =====
    $startMobile = max($current - 2, 1);
    $endMobile = min($current + 1, $last);
    @endphp


    {{-- DESKTOP PAGINATION --}}
    <div class="hidden sm:flex">
        @for ($i = $startDesktop; $i <= $endDesktop; $i++)
            @if ($i==$current)
            <button
            wire:key="paging-{{ $i }}"
            class="px-3 py-2 
                       border border-blue-600 dark:border-blue-500
                       bg-blue-600 dark:bg-blue-500 
                       text-sm text-white font-medium 
                       shadow-sm">
            {{ $i }}
            </button>
            @else
            <button
                wire:click="gotoPage({{ $i }})"
                wire:key="paging-{{ $i }}"
                class="px-3 py-2 text-sm 
                       border border-slate-300 dark:border-slate-700 
                       bg-white dark:bg-slate-900 
                       text-slate-700 dark:text-slate-200 
                       hover:border-blue-500 dark:hover:border-blue-400 
                       transition">{{ $i }}</button>
            @endif
            @endfor
    </div>


    {{-- MOBILE PAGINATION --}}
    <div class="flex sm:hidden">
        @for ($i = $startMobile; $i <= $endMobile; $i++)
            @if ($i==$current)
            <button
            wire:key="paging-m-{{ $i }}"
            class="px-3 py-2 
                       border border-blue-600 dark:border-blue-500
                       bg-blue-600 dark:bg-blue-500 
                       text-sm text-white font-medium 
                       shadow-sm">
            {{ $i }}
            </button>
            @else
            <button
                wire:click="gotoPage({{ $i }})"
                wire:key="paging-m-{{ $i }}"
                class="px-3 py-2 text-sm 
                       border border-slate-300 dark:border-slate-700 
                       bg-white dark:bg-slate-900 
                       text-slate-700 dark:text-slate-200 
                       hover:border-blue-500 dark:hover:border-blue-400 
                       transition">{{ $i }}</button>
            @endif
            @endfor
    </div>


    {{-- Tombol Selanjutnya --}}
    @if ($paginator->hasMorePages())
    <button wire:click="nextPage"
        class="px-3 py-2 text-sm 
               border border-slate-300 dark:border-slate-700 
               bg-white dark:bg-slate-900 
               text-slate-700 dark:text-slate-200 
               hover:border-blue-500 dark:hover:border-blue-400 
               transition">»</button>
    @else
    <button class="px-3 py-2 text-sm 
                   border border-slate-300 dark:border-slate-700 
                   bg-white dark:bg-slate-900 
                   text-slate-300 dark:text-slate-600 
                   cursor-not-allowed" disabled>»</button>
    @endif


    {{-- Tombol Paling Akhir --}}
    @if ($paginator->hasMorePages())
    <button wire:click="gotoPage({{ $last }})"
        class="px-3 py-2 text-sm 
               rounded-e-sm border border-slate-300 dark:border-slate-700 
               bg-white dark:bg-slate-900 
               text-slate-700 dark:text-slate-200 
               hover:border-blue-500 dark:hover:border-blue-400 
               transition">»»</button>
    @else
    <button class="px-3 py-2 text-sm 
                   rounded-e-sm border border-slate-300 dark:border-slate-700 
                   bg-white dark:bg-slate-900 
                   text-slate-300 dark:text-slate-600 
                   cursor-not-allowed" disabled>»»</button>
    @endif

</div>
@endif