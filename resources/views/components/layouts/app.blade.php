@props([
'title' => ''
])

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="/ajuin.png">
    <title>{{ $title ?? config('app.name', 'Laravel App') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        window.applyTheme = function(withTransition = false) {
            const theme = localStorage.getItem('theme') || 'system';
            const html = document.documentElement;
            const isDark = theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);

            if (!withTransition) {
                html.classList.add('no-transitions');
            }

            if (isDark) {
                html.classList.add('dark');
                html.setAttribute('data-theme', 'dark');
            } else {
                html.classList.remove('dark');
                html.setAttribute('data-theme', 'light');
            }

            if (!withTransition) {
                requestAnimationFrame(() => {
                    void html.offsetHeight;
                    html.classList.remove('no-transitions');
                });
            }
        };
        applyTheme(false);
    </script>

    <style>
        html {
            overflow-y: scroll;
            scrollbar-gutter: stable;
        }

        .no-transitions,
        .no-transitions *,
        .no-transitions *:before,
        .no-transitions *:after {
            transition: none !important;
            animation: none !important;
        }

        /* FIX: Mencegah drawer-toggle mengunci scroll di layar desktop (lg ke atas) */
        @media (min-width: 1024px) {

            .drawer-toggle:checked~.drawer-content,
            html:has(.drawer-toggle:checked) {
                overflow: visible !important;
                height: auto !important;
            }

            /* Jika menggunakan DaisyUI, ini akan memaksa body tetap bisa scroll */
            body:has(#mobile-drawer:checked) {
                overflow: auto !important;
            }
        }

        [x-cloak] {
            display: none !important;
        }
    </style>

    @livewireStyles
</head>

<body class="min-h-screen theme-transition
             bg-slate-300 dark:bg-slate-950
             text-slate-700 dark:text-slate-100">

    <div x-data="{ 
            open: localStorage.getItem('sidebar_open') === null ? true : localStorage.getItem('sidebar_open') === 'true',
            isMobile: window.innerWidth < 1024
        }"
        x-init="
            $watch('open', value => {
                localStorage.setItem('sidebar_open', value);
            });
            
            window.addEventListener('resize', () => {
                isMobile = window.innerWidth < 1024;
            });

            document.addEventListener('livewire:navigated', () => {
                if (window.innerWidth < 1024) {
                    open = false;
                }
            });
        "
        class="relative min-h-screen"
        x-cloak>

        {{-- Checkbox hanya aktif sebagai toggle drawer di mobile --}}
        <input id="mobile-drawer" type="checkbox" class="drawer-toggle lg:hidden" x-model="open" />

        <x-navbar :title="$title" />

        <x-sidebar :title="$pageTitle ?? ''" />

        {{-- Main Content --}}
        <div class="pt-16 min-h-screen theme-transition transition-all duration-300"
            :class="{
                 'ml-0': isMobile,
                 'lg:ml-16': !isMobile
                    }">
            <div class="p-4 justify-center mx-auto overflow-y-auto">
                <div class="p-8 w-full max-w-4xl mx-auto
                    rounded-xl border theme-transition
                    bg-radial-[at_25%_25%] from-slate-100 to-slate-200 to-75%
                    bg-radial-[at_25%_25%] dark:from-slate-950 dark:to-slate-950 to-75%
                    border-slate-300 dark:border-slate-800
                    shadow-sm dark:shadow-none
                    backdrop-blur-sm">
                    {{ $slot }}
                </div>
            </div>
        </div>

    </div>

    <x-ui.toast />
    @livewireScripts
</body>

</html>