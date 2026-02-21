@props([
'title' => ''
])

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/ajuin.png">
    <title>{{ $title ?? config('app.name', 'Laravel App') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-slate-100 dark:bg-slate-950 min-h-screen ">

    <div x-data="{ open: localStorage.getItem('sidebar_open') === 'true' }"
        x-init="$watch('open', value => localStorage.setItem('sidebar_open', value))"
        class="drawer lg:drawer-open">

        <input id="my-drawer-4"
            type="checkbox"
            class="drawer-toggle"
            x-model="open" />

        {{-- CONTENT --}}
        <div class="drawer-content">
            {{-- Navbar --}}
            <x-navbar :title="$title" />

            {{-- Page Content --}}
            <div class="p-4 max-w-[800px] justify-center mx-auto">
                <div class="p-8 w-full max-w-2xl mx-auto
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

    <div class="toast toast-top toast-end z-50">
        @if (session()->has('success'))
        <div class="alert alert-success shadow-lg text-sm"
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 3000)">
            <svg xmlns="http://www.w3.org" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if (session()->has('error'))
        <div class="alert alert-error shadow-lg text-sm"
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 3000)">
            <svg xmlns="http://www.w3.org" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('error') }}</span>
        </div>
        @endif
    </div>
    @livewireScripts
</body>

</html>