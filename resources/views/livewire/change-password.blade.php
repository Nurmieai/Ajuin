<dialog id="changePasswordModal" class="modal" wire:ignore.self>
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">Ganti Password</h3>

        <input wire:model.defer="old_password"
               type="password"
               class="input input-bordered w-full mb-2"
               placeholder="Password Lama">
        @error('old_password') <p class="text-error">{{ $message }}</p> @enderror

        <input wire:model.defer="password"
               type="password"
               class="input input-bordered w-full mb-2"
               placeholder="Password Baru">
        @error('password') <p class="text-error">{{ $message }}</p> @enderror

        <input wire:model.defer="password_confirmation"
               type="password"
               class="input input-bordered w-full"
               placeholder="Konfirmasi Password">
        @error('password_confirmation') <p class="text-error">{{ $message }}</p> @enderror

        <div class="modal-action">
            <button class="btn btn-ghost"
                    onclick="changePasswordModal.close()">
                Batal
            </button>

            <button class="btn btn-primary"
                    wire:click="update"
                    wire:loading.attr="disabled">
                Simpan
            </button>
        </div>
    </div>
<script>
    window.addEventListener('password-updated', () => {
        changePasswordModal.close();
    });
</script>
</dialog>
