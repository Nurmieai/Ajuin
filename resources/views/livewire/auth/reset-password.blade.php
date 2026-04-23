<div class="min-h-screen flex items-center justify-center">

    <div class="w-full max-w-2xl">

        <div class="grid md:grid-cols-2
                    bg-slate-50 dark:bg-slate-900
                    border border-slate-200 dark:border-slate-800
                    shadow-lg rounded-xl overflow-hidden">

            {{-- Left Logo --}}
            <div class="hidden md:flex items-center justify-center
                        bg-slate-100 dark:bg-slate-800 p-6">
                <img src="/ajuin.png"
                    alt="Logo"
                    class="w-72 object-contain">
            </div>

            {{-- Right Form --}}
            <div class="p-6 md:p-8">

                <div class="mb-6 text-center md:text-left">
                    <h1 class="text-xl md:text-2xl font-semibold
                               text-slate-800 dark:text-slate-100">
                        Reset Password
                    </h1>

                    <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Masukkan password baru untuk akun Anda
                    </p>
                </div>

                <form wire:submit.prevent="resetPassword" class="space-y-4">

                    <input type="hidden" wire:model="token">


                    <x-ui.input
                        name="password"
                        type="password"
                        label="Password Baru"
                        placeholder="Minimal 8 karakter"
                        wire:model.defer="password" />

                    <x-ui.input
                        name="password_confirmation"
                        type="password"
                        label="Konfirmasi Password"
                        placeholder="Ulangi password baru"
                        wire:model.defer="password_confirmation" />

                    <button type="submit"
                        class="w-full py-2 rounded-lg
                               bg-blue-600 hover:bg-blue-500
                               text-white text-sm font-medium
                               transition">

                        <span wire:loading.remove>
                            Reset Password
                        </span>

                        <span wire:loading
                            class="loading loading-spinner loading-xs">
                        </span>
                    </button>

                </form>

                {{-- Link kembali --}}
                <div class="mt-4 text-xs text-center md:text-left">
                    <a wire:navigate href="{{ route('login') }}"
                        class="text-slate-500 hover:text-blue-600 transition">
                        Kembali ke Login
                    </a>
                </div>

                {{-- Flash Success --}}
                @if (session()->has('message'))
                <div class="mt-6
                                bg-green-50 dark:bg-green-900/30
                                border border-green-200 dark:border-green-800
                                text-green-700 dark:text-green-300
                                text-sm rounded-lg p-3">
                    {{ session('message') }}
                </div>
                @endif

                {{-- Flash Error --}}
                @if (session()->has('error'))
                <div class="mt-6
                                bg-red-50 dark:bg-red-900/30
                                border border-red-200 dark:border-red-800
                                text-red-700 dark:text-red-300
                                text-sm rounded-lg p-3">
                    {{ session('error') }}
                </div>
                @endif

            </div>
        </div>

    </div>
</div>