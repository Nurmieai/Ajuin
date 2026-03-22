@props(['title' => 'Login'])

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="/ajuin.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name') }}</title>

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
             text-slate-900 dark:text-slate-100 
             overflow-hidden flex items-center justify-center">

    {{ $slot }}

    @livewireScripts
</body>

</html>