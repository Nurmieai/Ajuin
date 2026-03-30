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

        <a wire:navigate class="btn btn-warning flex-1 join-item"
            href="{{ route('student.submission-letter-check') }}">
            Cek Pengajuan Surat
        </a>

        <a wire:navigate class="btn btn-ghost flex-1 join-item"
            href="{{ route('student.submission-manage') }}">
            Cek Pengajuan PKL
        </a>

        <a wire:navigate class="btn btn-success flex-1 join-item"
            href="{{ route('student.ulasan-pkl') }}">
            Ulasan PKL
        </a>
    </div>

    {{-- Ganti modal lama dengan ini --}}

    {{-- Modal Konfirmasi Generate --}}
    <x-ui.confirmation
        :open="$confirmingAction === 'generate'"
        type="info"
        title="Konfirmasi Pengajuan Surat"
        message="Apakah Anda yakin data pribadi sudah benar? Pastikan nama, NISN, jurusan, tanggal lahir, dan alamat sudah sesuai."
        confirmText="Ya, Generate"
        cancelText="Batal"
        confirmAction="generateLetter" />

    {{-- Modal Warning Profile --}}
    <x-ui.confirmation
        :open="$confirmingAction === 'profile_incomplete'"
        type="danger"
        title="Data Pribadi Belum Lengkap"
        message="Anda belum dapat mengajukan surat PKL karena beberapa data pribadi belum diisi."
        confirmText="Lengkapi Profil"
        cancelText="Tutup"
        confirmAction="redirectToProfile" />

    {{-- Modal Warning Submission --}}
    <x-ui.confirmation
        :open="$confirmingAction === 'submission_pending'"
        type="danger"
        title="Pengajuan PKL Belum Disetujui"
        message="Kamu belum dapat mengajukan surat PKL karena pengajuan PKL kamu belum disetujui oleh guru."
        confirmText="Cek Pengajuan"
        cancelText="Tutup"
        confirmAction="redirectToSubmission" />

</div>