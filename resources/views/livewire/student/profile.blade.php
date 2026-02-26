<div class="min-h-screen py-10 px-4">

    <div class="max-w-5xl mx-auto space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">Profil PKL</h1>
                <p class="text-base-content/70">
                    Pastikan data lengkap sebelum mengajukan PKL.
                </p>
            </div>

            <div class="badge badge-success gap-2">
                Profil Aktif
            </div>
        </div>

        <form wire:submit.prevent="save" class="space-y-6">

            <!-- DATA PRIBADI -->
            <div class="card shadow-xl border border-base-300">
                <div class="card-body space-y-6">

                    <h2 class="text-lg font-semibold border-b pb-2">
                        Data Pribadi
                    </h2>

                    <div class="grid md:grid-cols-2 gap-6">

                        <div>
                            <label class="label-text text-sm opacity-70">Nama Lengkap</label>
                            <input type="text"
                                wire:model="fullname"
                                class="input input-bordered w-full mt-1">
                        </div>

                        <div>
                            <label class="label-text text-sm opacity-70">NISN</label>
                            <input type="text"
                                wire:model="nisn"
                                class="input input-bordered w-full mt-1">
                        </div>

                        <div>
                            <label class="label-text text-sm opacity-70">Kelas</label>
                            <input type="text"
                                wire:model="class"
                                class="input input-bordered w-full mt-1">
                        </div>

                        <div>
                            <label class="label-text text-sm opacity-70">No. Telepon</label>
                            <input type="text"
                                wire:model="phone"
                                class="input input-bordered w-full mt-1">
                        </div>

                        <div class="md:col-span-2">
                            <label class="label-text text-sm opacity-70">Alamat Lengkap</label>
                            <textarea wire:model="address"
                                rows="3"
                                class="textarea textarea-bordered w-full mt-1"></textarea>
                        </div>

                    </div>

                </div>
            </div>


            <!-- DATA PKL -->
            <div class="card shadow-xl border border-base-300">
                <div class="card-body space-y-6">

                    <h2 class="text-lg font-semibold border-b pb-2">
                        Data PKL
                    </h2>

                    <div class="grid md:grid-cols-2 gap-6">

                        <div>
                            <label class="label-text text-sm opacity-70">Jurusan</label>
                            <select wire:model="major"
                                class="select select-bordered w-full mt-1">
                                <option value="">Pilih Jurusan</option>
                                <option value="RPL">RPL</option>
                                <option value="TKJ">TKJ</option>
                                <option value="MM">MM</option>
                            </select>
                        </div>

                        <div>
                            <label class="label-text text-sm opacity-70">Minat PKL</label>
                            <input type="text"
                                wire:model="interest"
                                class="input input-bordered w-full mt-1"
                                placeholder="Contoh: Web Developer">
                        </div>

                        <div class="md:col-span-2">
                            <label class="label-text text-sm opacity-70">
                                Catatan Tambahan
                            </label>
                            <textarea wire:model="note"
                                rows="3"
                                class="textarea textarea-bordered w-full mt-1"></textarea>
                        </div>

                    </div>

                </div>
            </div>

            <!-- ACTION -->
            <div class="flex justify-end gap-3">
                <button type="submit"
                    class="btn btn-primary"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>Simpan Perubahan</span>
                    <span wire:loading class="loading loading-spinner loading-sm"></span>
                </button>
            </div>

        </form>

    </div>
</div>
