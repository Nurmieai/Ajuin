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
    @livewireStyles
</head>

<body class="bg-slate-100 dark:bg-slate-950 min-h-screen">

    <div x-data="{ 
            open: localStorage.getItem('sidebar_open') === null ? true : localStorage.getItem('sidebar_open') === 'true',
            isMobile: window.innerWidth < 1024,
            sidebarWidth: '16' // default collapsed width (w-16 = 4rem)
        }"
        x-init="
            $watch('open', value => {
                localStorage.setItem('sidebar_open', value);
                sidebarWidth = value ? '56' : '16'; // 56 = w-56 (14rem), 16 = w-16 (4rem)
            });
            // Set initial width
            sidebarWidth = open ? '56' : '16';
            
            window.addEventListener('resize', () => {
                isMobile = window.innerWidth < 1024;
            });
        "
        class="relative min-h-screen">

        <!-- Mobile Drawer Toggle (hidden on desktop) -->
        <input id="mobile-drawer" type="checkbox" class="drawer-toggle lg:hidden" x-model="open" />

        <!-- Navbar - Fixed di atas, overlay mode -->
        <x-navbar :title="$title" />

        <!-- Sidebar Component - Fixed di kiri bawah navbar -->
        <x-sidebar :title="$pageTitle ?? ''" />

        <!-- Main Content - Margin kiri tetap (collapsed width), tidak bergeser -->
        <div class="transition-all duration-300 ease-in-out pt-16 lg:ml-16 min-h-screen h-screen overflow-y-auto"
            :class="{
                 'ml-0': isMobile,
                 'lg:ml-16': !isMobile // Selalu pakai margin collapsed (w-16)
             }">

            <!-- Page Content -->
            <div class="p-4 justify-center mx-auto">
                <div class="p-8 w-full max-w-4xl mx-auto
                            bg-white dark:bg-slate-950
                            border border-slate-200 dark:border-slate-800
                            rounded-xl">
                    {{ $slot }}
                </div>
            </div>
        </div>

    </div>

    @livewireScripts
</body>
<script>
document.addEventListener('livewire:navigated', () => {

    // reset scroll
    window.scrollTo(0, 0);

    // hapus class pengunci scroll
    document.body.classList.remove('modal-open');
    document.body.style.overflow = 'auto';

    // force close semua dialog
    document.querySelectorAll('dialog').forEach(d => {
        try {
            d.close();
        } catch (e) {}
        d.removeAttribute('open');
    });

    // HAPUS backdrop yang nyangkut (ini penting banget)
    document.querySelectorAll('.modal-backdrop').forEach(el => {
        el.remove();
    });
});
</script>
</html>