<template x-teleport="body">
    <dialog
        id="changePasswordModal"
        class="modal backdrop-blur-sm"
        wire:ignore.self>

        <div class="modal-box w-full max-w-md bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 shadow-xl rounded-xl p-0 overflow-hidden flex flex-col max-h-[90vh]">

            {{-- Header --}}
            <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-900/50 shrink-0">
                <div>
                    <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100">Ganti Password</h3>
                    <p class="text-xs text-slate-500 mt-1">Gunakan kombinasi password yang kuat demi keamanan akun.</p>
                </div>
                <button type="button" onclick="changePasswordModal.close()" class="btn btn-sm btn-circle btn-ghost">✕</button>
            </div>

            {{-- Body --}}
            <div class="p-8 space-y-6 overflow-y-auto">
                <form id="changePasswordForm" wire:submit.prevent="update" class="space-y-5">
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
                        label="Konfirmasi Password Baru"
                        placeholder="Ulangi password baru"
                        wire:model.defer="password_confirmation" />
                </form>
            </div>

            {{-- Footer --}}
            <div class="p-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-800 flex justify-end gap-3 shrink-0">
                <button
                    type="button"
                    onclick="changePasswordModal.close()"
                    class="btn px-8 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-200 border-none hover:bg-slate-300">
                    Batal
                </button>

                <button
                    form="changePasswordForm"
                    type="submit"
                    class="btn px-8 bg-blue-600 hover:bg-blue-700 text-white border-none shadow-lg shadow-blue-500/20"
                    wire:loading.attr="disabled"
                    wire:target="update">

                    <span wire:loading.remove wire:target="update">Simpan Perubahan</span>
                    <span wire:loading wire:target="update" class="flex items-center gap-2">
                        <span class="loading loading-spinner loading-xs"></span> Menyimpan...
                    </span>
                </button>
            </div>
        </div>

        {{-- Backdrop Click --}}
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>

        <script>
            window.addEventListener('password-updated', () => {
                changePasswordModal.close();
            });
        </script>
    </dialog>
</template>