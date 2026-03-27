{{-- resources/views/components/breadcrumbs.blade.php --}}
@props([
'items' => [],
'textSize' => 'text-xs sm:text-sm', // Default: kecil di HP, normal di Desktop
'iconSize' => 'xs', // Default: ukuran 'xs' (sesuai array size polos di komponen icon Anda)
])

<nav class="breadcrumbs {{ $textSize }} text-slate-500 dark:text-slate-400 p-0 theme-transition 
            max-w-full overflow-x-auto [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">

    <ul class="flex items-center gap-1 sm:gap-2 theme-transition">

        {{-- Item Home Selalu Ada --}}
        <li>
            <a wire:navigate href="{{ route('dashboard') }}"
                class="inline-flex items-center gap-1 sm:gap-2 hover:text-blue-600 dark:hover:text-blue-400 theme-transition">
                <x-ui.icon name="home" size="{{ $iconSize }}" />
                <span>Home</span>
            </a>
        </li>

        @foreach ($items as $label => $config)
        @php
        // Mendukung format ['Label' => 'URL'] atau ['Label' => ['url' => '...', 'icon' => '...']]
        $url = is_array($config) ? ($config['url'] ?? '#') : $config;
        $icon = is_array($config) ? ($config['icon'] ?? null) : null;
        $isLast = $loop->last;
        @endphp

        <li>
            @if (!$isLast)
            {{-- Item Link (Bukan yang terakhir) --}}
            <a wire:navigate href="{{ $url }}"
                class="inline-flex items-center gap-1 sm:gap-2 hover:text-blue-600 dark:hover:text-blue-400 theme-transition whitespace-nowrap">
                @if($icon)
                <x-ui.icon :name="$icon" size="{{ $iconSize }}" />
                @endif
                <span>{{ $label }}</span>
            </a>
            @else
            {{-- Item Aktif (Yang terakhir) --}}
            <span class="inline-flex items-center gap-1 sm:gap-2 font-semibold text-slate-900 dark:text-white theme-transition whitespace-nowrap">
                @if($icon)
                <x-ui.icon :name="$icon" size="{{ $iconSize }}" />
                @endif

                {{-- Batasi panjang teks di HP agar tidak terlalu memakan tempat --}}
                <span class="truncate max-w-[150px] sm:max-w-none">
                    {{ $label }}
                </span>
            </span>
            @endif
        </li>
        @endforeach
    </ul>
</nav>