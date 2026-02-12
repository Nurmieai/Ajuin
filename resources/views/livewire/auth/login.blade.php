<div class="min-h-screen flex items-center justify-center
            bg-slate-50 dark:bg-slate-950 px-4">

    <div class="w-full max-w-3xl">

        <div class="grid md:grid-cols-2
                    bg-white dark:bg-slate-900
                    border border-slate-200 dark:border-slate-800
                    shadow-lg rounded-xl overflow-hidden">

            <div class="hidden md:flex items-center justify-center
                        bg-slate-100 dark:bg-slate-800 p-6">

                <img src="{{ asset('assets/img/logo/Mahput.png') }}"
                    alt="Logo"
                    class="w-32 md:w-40 object-contain">
            </div>

            <div class="p-6 md:p-8">

                <div class="mb-6 text-center md:text-left">
                    <h1 class="text-xl md:text-2xl font-semibold
                               text-slate-800 dark:text-slate-100">
                        Selamat Datang
                    </h1>

                    <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Silakan login untuk melanjutkan
                    </p>
                </div>

                <form wire:submit.prevent="login" class="space-y-4">

                    <x-ui.input
                        name="email"
                        type="email"
                        label="Email"
                        placeholder="email@example.com"
                        wire:model.defer="email" />

                    <x-ui.input
                        name="password"
                        type="password"
                        label="Password"
                        placeholder="Masukkan password"
                        wire:model.defer="password" />

                    <div class="flex justify-start text-xs">
                        <a href="{{ route('register') }}"
                            class="text-slate-500 hover:text-blue-600 transition">
                            Daftar disini
                        </a>
                    </div>

                    <button type="submit"
                        class="w-full py-2 rounded-lg
                               bg-blue-600 hover:bg-blue-500
                               text-white text-sm font-medium
                               transition">

                        <span wire:loading.remove>Login</span>
                        <span wire:loading class="loading loading-spinner loading-xs"></span>
                    </button>

                </form>

                {{-- Flash Message --}}
                @if (session()->has('message'))
                <div class="mt-6
                            bg-amber-50 dark:bg-amber-900/30
                            border border-amber-200 dark:border-amber-800
                            text-amber-700 dark:text-amber-300
                            text-sm rounded-lg p-3">
                    {{ session('message') }}
                </div>
                @endif
            </div>
        </div>

    </div>
</div>