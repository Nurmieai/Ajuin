@props(['items' => []])

<nav class="breadcrumbs text-sm text-slate-500 dark:text-slate-400 p-0">
    <ul class="flex items-center gap-1">
        {{-- Item Home Selalu Ada --}}
        <li>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                <x-ui.icon name="home" class="w-4 h-4" />
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
            <a href="{{ $url }}" class="inline-flex items-center gap-2 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                @if($icon) <x-ui.icon :name="$icon" class="w-4 h-4" /> @endif
                <span>{{ $label }}</span>
            </a>
            @else
            <span class="inline-flex items-center gap-2 font-semibold text-slate-900 dark:text-white">
                @if($icon) <x-ui.icon :name="$icon" class="w-4 h-4" /> @endif
                <span>{{ $label }}</span>
            </span>
            @endif
        </li>
        @endforeach
    </ul>
</nav>