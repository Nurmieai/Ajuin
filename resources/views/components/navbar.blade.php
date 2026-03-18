@props([
'title' => ''
])

<!-- Navbar - Fixed full width, z-index lebih tinggi dari sidebar -->
<nav class="fixed top-0 left-0 right-0 z-40 w-full bg-white/80 backdrop-blur border-b border-slate-200 text-slate-800 dark:bg-slate-950/80 dark:border-slate-800 dark:text-slate-100 h-16"
    id="page-top">
    <div class="flex flex-row justify-between items-center h-full px-4">
        <!-- Left Side: Mobile Toggle + Title -->
        <div class="flex flex-row items-center gap-4">
            <!-- Mobile Menu Button -->
            <button @click="open = !open"
                class="lg:hidden btn btn-square btn-ghost"
                aria-label="Toggle sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor" class="inline-block size-5">
                    <path d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Toggle Button (Desktop) -->
            <div class="hidden lg:flex items-center justify-start">
                <button @click="open = !open"
                    class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                    :title="open ? 'Collapse sidebar' : 'Expand sidebar'">
                    <!-- Icon Collapse (panah ke kiri) -->
                    <svg x-show="open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2" fill="none" stroke="currentColor" class="w-5 h-5">
                        <path d="M11 17l-5-5 5-5M18 17l-5-5 5-5" />
                    </svg>
                    <!-- Icon Expand (panah ke kanan) -->
                    <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2" fill="none" stroke="currentColor" class="w-5 h-5">
                        <path d="M13 17l5-5-5-5M6 17l5-5-5-5" />
                    </svg>
                </button>
            </div>

            <!-- Title -->
            <div class="text-lg font-semibold">
                {{ $title }}
            </div>
        </div>

        <!-- Right Side: User Menu -->
        <div class="flex items-center">
            <div class="dropdown dropdown-end">
                <label tabindex="0" class="btn btn-ghost">
                    {{ ucwords(auth()->user()->username) }}
                </label>

                <ul tabindex="0"
                    class="menu dropdown-content bg-base-100 dark:bg-slate-950 rounded-box z-50 mt-4 w-52 p-2 shadow-lg border border-slate-200 dark:border-slate-800">
                    @role('student')
                    <li>
                        <a wire:navigate href="{{ route('student.profile') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Profil
                        </a>
                    </li>
                    @endrole
                    <li>
                        <button onclick="changePasswordModal.showModal()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                            Ganti Password
                        </button>
                    </li>
                    <div class="divider my-1"></div>
                    <li>
                        <button onclick="logoutModal.showModal()" class="text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<livewire:change-password />

<dialog id="logoutModal" class="modal">
    <div class="modal-box bg-white dark:bg-slate-800">
        <h3 class="font-bold text-lg text-slate-800 dark:text-slate-100">Konfirmasi Logout</h3>
        <p class="py-4 text-slate-600 dark:text-slate-300">Yakin mau logout?</p>

        <div class="modal-action">
            <button class="btn btn-ghost" onclick="logoutModal.close()">
                Batal
            </button>

            <form action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-error">
                    Logout
                </button>
            </form>
        </div>
    </div>
    <div onclick="this.closest('dialog').close()"  class="modal-backdrop">
        <button>close</button>
    </div>
</dialog>