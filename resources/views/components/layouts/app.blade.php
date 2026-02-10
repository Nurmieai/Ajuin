@props([
'title' => ''
])

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name', 'Laravel App') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="dark:bg-slate-950 min-h-screen ">

    <div class="drawer lg:drawer-open">
        <input id="my-drawer-4" type="checkbox" class="drawer-toggle" />

        {{-- CONTENT --}}
        <div class="drawer-content">
            {{-- Navbar --}}
            <x-navbar :title="$title" />

            {{-- Page Content --}}
            <div class="p-4 max-w-[800px] justify-center mx-auto">
                <div class="p-8 w-full max-w-2xl
                            bg-white dark:bg-slate-950
                            border border-slate-200 dark:border-slate-800
                            rounded-xl">
                    {{ $slot }}
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <x-sidebar :title="$pageTitle ?? ''" />
    </div>

    @livewireScripts
</body>

</html>