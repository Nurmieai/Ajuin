<x-slot:title>
    Layanan Akademik
</x-slot:title>

<div class="flex flex-col gap-4">
    <x-ui.breadcrumbs :items="[
        'Layanan Akademik' => [
            'url' => route('student.academic-service'),
            'icon' => 'academic-cap'
        ],
    ]" />

    <div class="flex w-full join">
        <button
            class="btn btn-primary flex-1 join-item"
            wire:click="confirmGenerate">
            Ajukan Surat
        </button>

        <a class="btn btn-warning flex-1 join-item">
            Cek Pengajuan Surat
        </a>

        <a class="btn btn-ghost flex-1 join-item"
            href="{{ route('student.submission-manage') }}">
            Cek Pengajuan PKL
        </a>

        <a class="btn btn-success flex-1 join-item"
            href="{{ route('student.ulasan-pkl') }}">
            Ulasan PKL
        </a>
    </div>

    <dialog id="generateModal" class="modal backdrop-blur-md" wire:ignore.self>
        <div class="modal-box">
            <h3 class="font-bold text-lg">Konfirmasi Pengajuan Surat</h3>

            <p class="py-4">
                Apakah Anda yakin data pribadi sudah benar?
            </p>

            <div class="alert alert-warning text-sm">
                <span>
                    Pastikan nama, NISN, jurusan, tanggal lahir, dan alamat sudah sesuai.
                </span>
            </div>

            <div class="modal-action">
                <button
                    class="btn btn-ghost"
                    onclick="generateModal.close()">
                    Batal
                </button>

                <button
                    class="btn btn-primary"
                    wire:click="generateLetter"
                    wire:loading.attr="disabled">

                    <span wire:loading.remove wire:target="generateLetter">
                        Ya, Generate
                    </span>

                    <span wire:loading wire:target="generateLetter"
                        class="loading loading-spinner loading-sm">
                    </span>
                </button>
            </div>
        </div>
    </dialog>

    <dialog id="profileWarningModal" class="modal backdrop-blur-md" wire:ignore.self>
        <div class="modal-box">
            <h3 class="font-bold text-lg text-warning">
                Data Pribadi Belum Lengkap
            </h3>
                <p class="py-4 text-sm">
                    Anda belum dapat mengajukan surat PKL karena beberapa data
                    pribadi belum diisi. Silakan lengkapi data profil terlebih dahulu.
                </p>
            <div class="alert alert-warning text-sm">
                <span>
                    Data yang wajib diisi:
                    Nama, NISN, Jurusan, Jenis Kelamin,
                    Tanggal Lahir, dan Alamat.
                </span>
            </div>

            <div class="modal-action">
                <button
                    class="btn btn-ghost"
                    onclick="profileWarningModal.close()">
                    Tutup
                </button>
                <a
                    href="{{ route('student.profile') }}"
                    class="btn btn-warning">
                    Lengkapi Profil
                </a>
            </div>

        </div>
    </dialog>
</div>

@script
<script>

    $wire.on('open-generate-modal', () => {
        generateModal.showModal();
    });

    $wire.on('close-generate-modal', () => {
        generateModal.close();
    });

    $wire.on('open-profile-warning', () => {
        profileWarningModal.showModal();
    });

</script>
@endscript
