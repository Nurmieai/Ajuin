@php
$menus = [
// Group 1
['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'home', 'group' => 1],

// Group 2
['label' => 'Bank PKL', 'route' => 'bankPKL', 'icon' => 'building-library', 'group' => 1],
['label' => 'Mitra PKL', 'route' => 'partners.index', 'icon' => 'academic-cap', 'group' => 1],
[
'label' => 'Pengajuan PKL',
'route' => auth()->user()->hasRole('student') ? 'student.submission-create' : 'teacher.submission-manage',
'icon' => 'edit',
'group' => 1
],
[
'label' => 'Layanan Akademik',
'route' => 'student.academic-service',
'icon' => 'briefcase',
'role' => 'student',
'group' => 1,
],
[
'label' => 'Surat PKL',
'route' => 'teacher.submission-letter',
'icon' => 'briefcase',
'role' => 'teacher',
'group' => 1,
],
[
'label' => 'Aktivasi Siswa',
'route' => 'teacher.activation',
'icon' => 'document-check',
'role' => 'teacher',
'group' => 1,
],
];
@endphp

<!-- Sidebar Container - Fixed di kiri, di bawah navbar -->
<div :class="{
        'w-fit': !open,
        'w-56': open,
        '-translate-x-full': isMobile && !open,
        'translate-x-0': isMobile && open || !isMobile
     }"
    class="fixed top-16 left-0 bottom-0 z-30 flex flex-col border-r border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-700 dark:text-slate-200 transition-all duration-300 shadow-lg">



    <!-- Menu Items -->
    <ul class="menu w-full grow gap-1 p-2 overflow-y-auto">
        @foreach([1] as $groupId)
        <div class="flex flex-col gap-1">
            @foreach(collect($menus)->where('group', $groupId) as $menu)
            @if(!isset($menu['role']) || auth()->user()->hasRole($menu['role']))
            <li>
                @php
                    $href = Route::has($menu['route']) ? route($menu['route']) : '#';
                    $isActive = request()->url() == $href;
                @endphp

                <a 
                    wire:navigate
                    href="{{ $href }}"
                    x-data="{ tooltip: false }"
                    @mouseenter="!open && (tooltip = true)"
                    @mouseleave="tooltip = false"
                    class="relative flex items-center gap-3 px-3 py-3 rounded-lg transition-all duration-200
                        {{ $isActive ? 'bg-blue-600 dark:bg-blue-500 text-white' : 'hover:bg-slate-100 dark:hover:bg-slate-800' }}">

                    <x-ui.icon :name="$menu['icon']" class="w-5 h-5 shrink-0" />

                    <span x-show="open"
                        x-transition:enter="transition-opacity duration-200"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        class="whitespace-nowrap overflow-hidden text-sm font-medium">
                        {{ $menu['label'] }}
                    </span>

                    <!-- Tooltip -->
                    <div x-show="!open && tooltip"
                        x-transition
                        class="absolute left-full ml-2 px-2 py-1 bg-slate-800 text-white text-xs rounded whitespace-nowrap z-50 pointer-events-none">
                        {{ $menu['label'] }}
                        <div class="absolute left-0 top-1/2 -translate-x-1 -translate-y-1/2 border-4 border-transparent border-r-slate-800"></div>
                    </div>
                </a>
            </li>
            @endif
            @endforeach
        </div>
        @endforeach
    </ul>

    <!-- Bottom Toggle (Alternative) -->
    <!-- <div class="p-2 border-t border-slate-200 dark:border-slate-800">
        <button @click="open = !open"
            class="w-full flex items-center justify-center gap-2 p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors text-xs text-slate-500">
            <span x-show="open">Collapse</span>
            <span x-show="!open">Expand</span>
        </button>
    </div> -->
</div>