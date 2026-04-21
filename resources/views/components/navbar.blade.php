@props([
'title' => ''
])

<nav id="page-top"
    class="fixed top-0 left-0 right-0 z-40 
           w-full h-16 theme-transition
           bg-radial-[at_25%_25%] from-slate-50/75 to-slate-50/75 to-75%
           bg-radial-[at_25%_25%] dark:from-slate-950/50 dark:to-slate-950/50 to-75%
           text-slate-800 dark:text-slate-100
           backdrop-blur border-b 
           border-slate-200 dark:border-slate-800">

    <div class="flex flex-row justify-between items-center h-full px-4">

        <div class="flex flex-row items-center gap-2 sm:gap-4">
            {{-- Mobile Sidebar Toggle --}}
            <button @click="open = !open"
                class="lg:hidden btn btn-sm btn-square btn-ghost"
                aria-label="Toggle sidebar">
                <x-ui.icon name="bars-3" size="sm" />
            </button>

            {{-- Desktop Sidebar Toggle --}}
            <div class="hidden lg:flex items-center justify-start theme-transition">
                <button @click="open = !open"
                    class="
                        hover:bg-slate-200 hover:dark:bg-slate-800 active:bg-slate-200 active:dark:bg-slate-800 
                        btn btn-ghost btn-sm sm:btn-md
                        p-2 rounded-lg
                        theme-transition "
                    :title="open ? 'Collapse sidebar' : 'Expand sidebar'">
                    <x-ui.icon x-show="open" name="chevron-double-left" size="sm" />
                    <x-ui.icon x-show="!open" name="chevron-double-right" size="sm" />
                </button>
            </div>

            {{-- Judul: Ukuran text lebih kecil di mobile agar tidak tabrakan --}}
            <div class="text-sm sm:text-lg font-bold truncate max-w-[150px] sm:max-w-none theme-transition">
                {{ $title }}
            </div>
        </div>

        <div class="flex items-center gap-1 sm:gap-2">

            {{-- Dropdown Tema --}}
            <div class="dropdown dropdown-end" wire:ignore>
                <div tabindex="0" role="button"
                    title="Ganti Tema"
                    class="
                        cursor-pointer
                        hover:bg-slate-200 hover:dark:bg-slate-900 active:bg-slate-200 active:dark:bg-slate-900 
                        btn btn-ghost btn-sm sm:btn-md
                        px-2 flex items-center gap-1 
                        theme-transition  ">
                    <x-ui.icon name="swatch" size="sm" />
                    <span class="hidden sm:inline-block font-medium">Tema</span>
                    <x-ui.icon name="chevron-down" size="xs" class="opacity-50" />
                </div>
                <ul tabindex="0"
                    class="dropdown-content z-[100] menu mt-7 p-2 shadow-xl backdrop-blur border rounded-xl w-40 sm:w-52 theme-transition
                           bg-radial-[at_25%_25%] from-slate-50/95 to-slate-50/95 to-75%
                           bg-radial-[at_25%_25%] dark:from-slate-950/95 dark:to-slate-950/95 to-75%
                           border-slate-200 dark:border-slate-800 
                           backdrop-blur-sm
                           ">
                    <li class="menu-title text-xs opacity-50">Pilih Tema</li>
                    <li>
                        <input
                            type="radio"
                            name="theme-dropdown"
                            class="theme-controller 
                                   hover:bg-slate-200 hover:dark:bg-slate-800 checked:bg-blue-500
                                   checked:hover:bg-blue-600 checked:hover:dark:bg-blue-500 
                                   checked:border-blue-500 
                                   hover:dark:text-white 
                                   btn btn-sm btn-ghost 
                                   justify-start"
                            aria-label="Sistem"
                            value="system" />
                    </li>
                    <li>
                        <input
                            type="radio"
                            name="theme-dropdown"
                            class="theme-controller 
                                   hover:bg-slate-200 hover:dark:bg-slate-800 checked:bg-blue-500
                                   checked:hover:bg-blue-600 checked:hover:dark:bg-blue-500 
                                   checked:border-blue-500 
                                   hover:dark:text-white 
                                   btn btn-sm btn-ghost 
                                   justify-start"
                            aria-label="Terang"
                            value="light" />
                    </li>
                    <li>
                        <input
                            type="radio"
                            name="theme-dropdown"
                            class="theme-controller 
                                   hover:bg-slate-200 hover:dark:bg-slate-800 checked:bg-blue-500
                                   checked:hover:bg-blue-600 checked:hover:dark:bg-blue-500 
                                   checked:border-blue-500 
                                   hover:dark:text-white 
                                   btn btn-sm btn-ghost 
                                   justify-start"
                            aria-label="Gelap"
                            value="dark" />
                    </li>
                </ul>
            </div>

            {{-- Dropdown User --}}
            <div class="dropdown dropdown-end">
                <label tabindex="0"
                    title="{{ auth()->user()->username }}"
                    class="
                            hover:bg-slate-200 hover:dark:bg-slate-900 active:bg-slate-200 active:dark:bg-slate-900 
                            btn btn-ghost btn-sm sm:btn-md 
                            px-2 sm:px-4 flex items-center gap-2 
                            theme-transition">
                    <x-ui.icon name="user-circle" size="sm" />
                    <span class="hidden sm:inline-block font-medium">{{ ucwords(auth()->user()->username) }}</span>
                    <x-ui.icon name="chevron-down" size="xs" class="opacity-50" />
                </label>

                <ul tabindex="0"
                    class="menu dropdown-content z-[100] mt-7 p-2 shadow-xl border rounded-xl w-52 theme-transition
                           bg-slate-50/95 dark:bg-slate-950/95
                           border-slate-200 dark:border-slate-800
                           backdrop-blur-md">

                    <li class="sm:hidden px-4 py-2 font-bold text-blue-600 dark:text-blue-400 border-b border-slate-100 dark:border-slate-800 mb-1">
                        Hallo, {{ auth()->user()->username }}
                    </li>

                    @role('student')
                    <li>
                        <a wire:navigate href="{{ route('student.profile') }}" class="flex items-center gap-2 py-2">
                            <x-ui.icon name="user" size="sm" />
                            Profil
                        </a>
                    </li>
                    @endrole

                    <li>
                        <button onclick="changePasswordModal.showModal()" class="flex items-center gap-2 py-2">
                            <x-ui.icon name="key" size="sm" />
                            Ganti Password
                        </button>
                    </li>

                    <div class="divider my-1 opacity-50"></div>

                    <li>
                        <button onclick="logoutModal.showModal()"
                            class="flex items-center gap-2 text-red-500 py-2
                                   hover:bg-red-50 dark:hover:bg-red-900/20 theme-transition">
                            <x-ui.icon name="arrow-left-on-rectangle" size="sm" />
                            Logout
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<livewire:change-password />

{{-- Modal Logout tetap sama seperti sebelumnya --}}
<dialog id="logoutModal" class="modal modal-bottom sm:modal-middle bg-transparent backdrop-blur-sm transition-all duration-300">
    <div class="modal-box p-0 overflow-hidden bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 shadow-2xl rounded-xl theme-transition">
        <div class="p-6 pb-0 flex flex-col items-center sm:items-start sm:flex-row gap-4">
            <div class="shrink-0 flex items-center justify-center size-12 rounded-full bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-500">
                <x-ui.icon name="arrow-left-on-rectangle" size="lg" />
            </div>
            <div class="text-center sm:text-left pt-1">
                <h3 class="font-bold text-xl text-slate-800 dark:text-slate-100">Konfirmasi Logout</h3>
                <p class="py-2 text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
                    Yakin ingin keluar? Sesi Anda akan berakhir.
                </p>
            </div>
        </div>
        <div class="modal-action bg-slate-50 dark:bg-slate-900/50 p-4 mt-6 flex flex-col-reverse sm:flex-row justify-end gap-3 border-t border-slate-200 dark:border-slate-800">
            <button type="button" class="btn btn-ghost px-8" onclick="logoutModal.close()">Batal</button>
            <form action="{{ route('logout') }}" method="GET">
                @csrf
                <button type="submit" class="btn px-8 bg-red-600 hover:bg-red-700 border-none text-white shadow-lg shadow-red-500/20 w-full sm:w-auto">
                    Logout Sekarang
                </button>
            </form>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop bg-transparent dark:bg-slate-950/40">
        <button>close</button>
    </form>
</dialog>

<style>
    #logoutModal[open] {
        animation: modal-pop 0.3s ease-out forwards;
    }

    @keyframes modal-pop {
        from {
            opacity: 0;
            transform: scale(0.95) translateY(10px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
</style>