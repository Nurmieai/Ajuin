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
        <input id="my-drawer-4" type="checkbox" class="drawer-toggle" />

        {{-- CONTENT --}}
        <div class="drawer-content">
            {{-- Navbar --}}
            <x-admin-navbar />

            {{-- Page Content --}}
            <div class="p-4">
                {{ $slot }}
            </div>
        </div>

        {{-- Sidebar --}}
        <x-admin-sidebar />
    </div>

    @livewireScripts
</body>

</html>
