<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name', 'Laravel App') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-gray-100 min-h-screen">

<div class="drawer lg:drawer-open">

    {{-- Toggle Mobile --}}
    <input id="app-drawer" type="checkbox" class="drawer-toggle" />

    {{-- ================= CONTENT ================= --}}
    <div class="drawer-content flex flex-col">

        {{-- Navbar --}}
        @isset($navbar)
            {{ $navbar }}
        @else
            <x-navbar />
        @endisset

        {{-- Page Content --}}
        <main class="flex-1 p-4 md:p-6">
            {{ $slot }}
        </main>

    </div>

    {{-- ================= SIDEBAR ================= --}}
    <div class="drawer-side">
        <label for="app-drawer" class="drawer-overlay"></label>

        @isset($sidebar)
            {{ $sidebar }}
        @else
            <x-sidebar />
        @endisset
    </div>

</div>

@livewireScripts
</body>
</html>
