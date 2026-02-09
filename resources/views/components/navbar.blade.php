@props([
    'title' => ''
])

<nav class="navbar w-full bg-slate-950 text-slate-300 justify-between">
    <div class="flex flex-row justify-start items-center">
        <label for="my-drawer-4" aria-label="open sidebar" class="btn btn-square btn-ghost lg:hidden">

            {{-- Sidebar Toggle Icon --}}
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor" class="my-1.5 inline-block size-4">

                <path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z"></path>
                <path d="M9 4v16"></path>
                <path d="M14 10l2 2l-2 2"></path>

            </svg>
        </label>

        <div class="px-4">
            {{ $title }}
        </div>
    </div>
    <div class="flex grow justify-end px-2">
        <div class="flex item-stretch">
        <div class="dropdown dropdown-end">
                <label tabindex="0" class="btn btn-ghost">
                    {{ ucwords(auth()->user()->username) }}
                </label>

                <ul tabindex="0"
                    class="menu dropdown-content bg-base-200 rounded-box z-1 mt-4 w-52 p-2 shadow-sm">
                    <li>
                        <button onclick="changePasswordModal.showModal()">
                            Ganti Password
                        </button>
                    </li>
                    <li>
                        <button onclick="logoutModal.showModal()" class="text-red-500">
                            Logout
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>

</nav>

<dialog id="changePasswordModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">Ganti Password</h3>

        <form method="POST" action="{{ route('password-update') }}" class="space-y-3">
            @csrf

            <input type="password"
                   name="old_password"
                   class="input input-bordered w-full"
                   placeholder="Password Lama">

            <input type="password"
                   name="password"
                   class="input input-bordered w-full"
                   placeholder="Password Baru">

            <input type="password"
                   name="password_confirmation"
                   class="input input-bordered w-full"
                   placeholder="Konfirmasi Password Baru">

            <div class="modal-action">
                <button type="button"
                        class="btn btn-ghost"
                        onclick="changePasswordModal.close()">
                    Batal
                </button>

                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</dialog>

<dialog id="logoutModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Konfirmasi Logout</h3>
        <p class="py-4">Yakin mau logout?</p>

        <div class="modal-action">
            <button class="btn btn-ghost"
                    onclick="logoutModal.close()">
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
</dialog>

