@php
$menus = [
// Group 1
['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'home', 'group' => 1],

// Group 2
['label' => 'Bank PKL', 'icon' => 'building-library', 'group' => 2, 'type' => 'button'],
['label' => 'Mitra PKL', 'route' => 'partners.index', 'icon' => 'academic-cap', 'group' => 2],
['label' => 'Pengajuan PKL', 'route' => auth()->user()->hasRole('student') ? 'student.submission-create' : 'teacher.submission-manage', 'icon' => 'edit', 'group' => 2],
['label' => 'Layanan Akademik', 'icon' => 'briefcase', 'group' => 2, 'type' => 'button'],

// Conditional Roles
['label' => 'Aktivasi Siswa', 'route' => 'teacher.activation', 'icon' => 'document-check', 'group' => 2, 'role' => 'teacher'],
['label' => 'Ulasan PKL', 'icon' => 'document-text', 'group' => 2, 'role' => 'student', 'type' => 'button'],
];
@endphp

<div class="drawer-side is-drawer-close:overflow-visible">
    <label for="my-drawer-4" aria-label="close sidebar" class="drawer-overlay"></label>

    <div class="flex flex-col min-h-full gap-4 items-start border-r border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-700 dark:text-slate-200 w-64 lg:w-auto">

        <ul class="menu w-full grow gap-[80px] h-screen p-0">
            @foreach([1, 2] as $groupId)
            <div class="flex flex-col h-max justify-center gap-2">
                {{-- Tombol Toggle khusus di Group 1 --}}
                @if($groupId == 1)
                <li>
                    <label for="my-drawer-4" class="justify-center w-auto is-drawer-open:w-[48px] is-drawer-open:px-[12] is-drawer-open:py-[6]">
                        {{-- Icon Toggle Manual sesuai SVG Anda --}}
                        <svg xmlns="http://www.w3.org" viewBox="0 0 24 24" stroke-width="2" fill="none" stroke="currentColor" class="my-1.5 inline-block size-5">
                            <path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z"></path>
                            <path d="M9 4v16"></path>
                            <path d="M14 10l2 2l-2 2"></path>
                        </svg>
                    </label>
                </li>
                @endif

                {{-- Render Menu Berdasarkan Group --}}
                @foreach(collect($menus)->where('group', $groupId) as $menu)
                @if(!isset($menu['role']) || auth()->user()->hasRole($menu['role']))
                <li>
                    @php
                    $tag = ($menu['type'] ?? 'link') === 'button' ? 'button' : 'a';
                    $href = $tag === 'a' ? (Route::has($menu['route']) ? route($menu['route']) : '#') : null;
                    @endphp

                    <{{ $tag }}
                        @if($href) href="{{ $href }}" @endif
                        class="is-drawer-close:tooltip is-drawer-close:tooltip-right {{ ($href && request()->url() == $href) ? 'active' : '' }}"
                        data-tip="{{ $menu['label'] }}">
                        <x-ui.icon :name="$menu['icon']" class="size-6" />
                        <span class="is-drawer-close:hidden">{{ $menu['label'] }}</span>
                    </{{ $tag }}>
                </li>
                @endif
                @endforeach
            </div>
            @endforeach
        </ul>
    </div>
</div>