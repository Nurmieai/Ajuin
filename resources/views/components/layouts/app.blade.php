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

            // 1. Kunci transisi jika tidak diinginkan
            if (!withTransition) {
                html.classList.add('no-transitions');
            }

            // 2. Update Theme
            if (isDark) {
                html.classList.add('dark');
                html.setAttribute('data-theme', 'dark');
            } else {
                html.classList.remove('dark');
                html.setAttribute('data-theme', 'light');
            }

            // 3. Lepas kunci transisi setelah browser melakukan "paint"
            if (!withTransition) {
                // Gunakan satu requestAnimationFrame saja untuk meminimalkan jeda
                requestAnimationFrame(() => {
                    // Paksa reflow agar browser sadar perubahan sudah terjadi
                    void html.offsetHeight;
                    html.classList.remove('no-transitions');
                });
            }
        };
        applyTheme(false);
    </script>

    <style>
        /* CSS untuk mematikan semua animasi secara total */
        .no-transitions,
        .no-transitions *,
        .no-transitions *:before,
        .no-transitions *:after {
            transition: none !important;
            animation: none !important;
        }
    </style>

    @livewireStyles
</head>

<body class="min-h-screen theme-transition
             bg-slate-100 dark:bg-slate-950
             text-slate-900 dark:text-slate-100">

    <div x-data="{ 
            open: localStorage.getItem('sidebar_open') === null ? true : localStorage.getItem('sidebar_open') === 'true',
            isMobile: window.innerWidth < 1024,
            sidebarWidth: '16'
        }"
        x-init="
            $watch('open', value => {
                localStorage.setItem('sidebar_open', value);
                sidebarWidth = value ? '56' : '16';
            });
            sidebarWidth = open ? '56' : '16';
            
            window.addEventListener('resize', () => {
                isMobile = window.innerWidth < 1024;
            });

            document.addEventListener('livewire:navigated', () => {
                if (window.innerWidth < 1024) {
                    open = false;
                }
            });
        "
        class="relative min-h-screen">

        <input id="mobile-drawer" type="checkbox" class="drawer-toggle lg:hidden" x-model="open" />

        <x-navbar :title="$title" />

        <x-sidebar :title="$pageTitle ?? ''" />

        <div class="pt-16 lg:ml-16 min-h-screen
                    theme-transition"
            :class="{
                 'ml-0': isMobile,
                 'lg:ml-16': !isMobile
             }">

            <div class="p-4 justify-center mx-auto">
                <div class="p-8 w-full max-w-4xl mx-auto
                            rounded-xl border theme-transition
                            bg-white dark:bg-slate-950/50
                            border-slate-200 dark:border-slate-800
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