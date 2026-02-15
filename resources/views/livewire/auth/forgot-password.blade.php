<div class="min-h-screen flex items-center justify-center
            bg-slate-50 dark:bg-slate-950 px-4">

    <div class="w-full max-w-3xl">

        <div class="grid md:grid-cols-2
                    bg-white dark:bg-slate-900
                    border border-slate-200 dark:border-slate-800
                    shadow-lg rounded-xl overflow-hidden">

            {{-- Left Side Logo --}}
            <div class="hidden md:flex items-center justify-center
                        bg-slate-100 dark:bg-slate-800 p-6">
                <img src="/ajuin.png"
                    alt="Logo"
                    class="w-72 object-contain">
            </div>

            {{-- Right Side Form --}}
            <div class="p-6 md:p-8">

                <div class="mb-6 text-center md:text-left">
                    <h1 class="text-xl md:text-2xl font-semibold
                               text-slate-800 dark:text-slate-100">
                        Lupa Password
                    </h1>

                    <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Masukkan email Anda untuk menerima link reset password
                    </p>
                </div>

                <form wire:submit.prevent="sendResetLink" class="space-y-4">

                    <x-ui.input
                        name="email"
                        type="email"
                        label="Email"
                        placeholder="email@example.com"
                        wire:model.defer="email" />

                    <button type="submit"
                        class="w-full py-2 rounded-lg
                               bg-blue-600 hover:bg-blue-500
                               text-white text-sm font-medium
                               transition">

                        <span wire:loading.remove>
                            Kirim Link Reset
                        </span>

                        <span wire:loading
                            class="loading loading-spinner loading-xs">
                        </span>
                    </button>

                </form>

                {{-- Link Kembali ke Login --}}
                <div class="mt-4 text-xs text-center md:text-left">
                    <a href="{{ route('login') }}"
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