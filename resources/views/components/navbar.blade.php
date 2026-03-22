@props([
'title' => ''
])

<nav id="page-top"
    class="fixed top-0 left-0 right-0 z-40 
           w-full h-16 theme-transition
           bg-white/80 dark:bg-slate-950/80 
           text-slate-800 dark:text-slate-100
           backdrop-blur border-b 
           border-slate-200 dark:border-slate-800">

    <div class="flex flex-row justify-between items-center h-full px-4">

        <div class="flex flex-row items-center gap-4">

            <button @click="open = !open"
                class="lg:hidden btn btn-square btn-ghost"
                aria-label="Toggle sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor" class="inline-block size-5">
                    <path d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div class="hidden lg:flex items-center justify-start theme-transition">
                <button @click="open = !open"
                    class="p-2 rounded-lg theme-transition
                           hover:bg-slate-100 dark:hover:bg-slate-800"
                    :title="open ? 'Collapse sidebar' : 'Expand sidebar'">

                    <svg x-show="open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2" fill="none" stroke="currentColor" class="w-5 h-5">
                        <path d="M11 17l-5-5 5-5M18 17l-5-5 5-5" />
                    </svg>

                    <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2" fill="none" stroke="currentColor" class="w-5 h-5">
                        <path d="M13 17l5-5-5-5M6 17l5-5-5-5" />
                    </svg>
                </button>
            </div>

            <div class="text-lg font-semibold theme-transition">
                {{ $title }}
            </div>
        </div>

        <div class="flex items-center gap-2">

            <div class="dropdown dropdown-end bg-transparent" wire:ignore>
                <div tabindex="0" role="button"
                    class="
                           bg-transparent m-1 font-medium cursor-pointer 
                           flex items-center gap-1 theme-transition
                           ">
                    Tema
                    <svg width="12.5px" height="12.5px" class="h-2 w-2 fill-current opacity-60" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2048 2048">
                        <path d="M1799 346l128 128-896 896-896-896 128-128 768 768 768-768z"></path>
                    </svg>
                </div>
                <ul tabindex="0"
                    class="dropdown-content z-[100] menu mt-5 p-2 shadow-xl border rounded-box w-52 theme-transition
                           bg-white/90 dark:bg-slate-950/90 
                           border-slate-200 dark:border-slate-800 
                           backdrop-blur-md">
                    <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-ghost justify-start" aria-label="System" value="system" /></li>
                    <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-ghost justify-start" aria-label="Light" value="light" /></li>
                    <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-ghost justify-start" aria-label="Dark" value="dark" /></li>
                </ul>
            </div>

            <div class="dropdown dropdown-end">
                <label tabindex="0" class="btn btn-ghost font-medium theme-transition">
                    {{ ucwords(auth()->user()->username) }}
                </label>

                <ul tabindex="0"
                    class="menu dropdown-content z-[100] mt-4 p-2 shadow-xl border rounded-box w-52 theme-transition
                           bg-white dark:bg-slate-950/80
                           border-slate-200 dark:border-slate-800">

                    @role('student')
                    <li>
                        <a wire:navigate href="{{ route('student.profile') }}" class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Profil
                        </a>
                    </li>
                    @endrole

                    <li>
                        <button onclick="changePasswordModal.showModal()" class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                            Ganti Password
                        </button>
                    </li>

                    <div class="divider my-1"></div>

                    <li>
                        <button onclick="logoutModal.showModal()"
                            class="flex items-center gap-2 text-red-500 
                                   hover:bg-red-50 dark:hover:bg-red-900/20 theme-transition">
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
    <div class="modal-box theme-transition
                bg-white dark:bg-slate-900">
        <h3 class="font-bold text-lg text-slate-800 dark:text-slate-100">
            Konfirmasi Logout
        </h3>
        <p class="py-4 text-slate-600 dark:text-slate-300">
            Yakin mau logout?
        </p>

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
    <div onclick="this.closest('dialog').close()" class="modal-backdrop">
        <button>close</button>
    </div>
</dialog>