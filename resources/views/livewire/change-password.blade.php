<dialog id="changePasswordModal"
    class="modal backdrop-blur-sm"
    wire:ignore.self>

    <div class="modal-box w-full max-w-md
                bg-white dark:bg-slate-900
                border border-slate-200 dark:border-slate-800
                shadow-xl rounded-xl ">

        <h3 class="text-lg font-semibold
                   text-slate-800 dark:text-slate-100
                   mb-6">
            Ganti Password
        </h3>

        <form wire:submit.prevent='update'>
                    <div class="space-y-4">

            <x-ui.input
                name="old_password"
                type="password"
                label="Password Lama"
                placeholder="Masukkan password lama"
                wire:model.defer="old_password" />

            <x-ui.input
                name="password"
                type="password"
                label="Password Baru"
                placeholder="Masukkan password baru"
                wire:model.defer="password" />

            <x-ui.input
                name="password_confirmation"
                type="password"
                label="Konfirmasi Password"
                placeholder="Ulangi password baru"
                wire:model.defer="password_confirmation" />

        </div>

        <div class="modal-action mt-8">

            <button
                type="button"
                class="px-4 py-2 rounded-lg
                    text-slate-600 dark:text-slate-300
                    hover:bg-slate-100 dark:hover:bg-slate-800
                    transition"
                onclick="changePasswordModal.close()">
                Batal
            </button>

            <button
                class="px-5 py-2 rounded-lg
                       bg-blue-600 hover:bg-blue-500
                       text-white
                       shadow-sm
                       disabled:opacity-50
                       transition"
                wire:loading.attr="disabled">

                <span wire:loading.remove>Simpan</span>
                <span wire:loading class="loading loading-spinner loading-xs"></span>
            </button>

        </div>
    </div>
        </form>
    <script>
        window.addEventListener('password-updated', () => {
            changePasswordModal.close();
        });
    </script>

</dialog>