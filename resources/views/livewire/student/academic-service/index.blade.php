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
            href="{{ route('student.review-pkl') }}">
            Ulasan PKL
        </a>
    </div>

    <x-ui.confirmation
        :open="$confirmingAction === 'generate'"
        type="info"
        title="Konfirmasi Pengajuan Surat"
        message="Sebelum melanjutkan, pastikan data Anda sudah sesuai — terutama Nama Lengkap, Jenis Kelamin, Link CV, dan Link Portofolio. Surat akan dibuat berdasarkan data yang tersimpan saat ini."
        confirmText="Ya, Ajukan Sekarang"
        cancelText="Periksa Dulu"
        confirmAction="generateLetter" />

    <x-ui.confirmation
        :open="$confirmingAction === 'profile_incomplete'"
        type="danger"
        title="Data Profil Belum Lengkap"
        message="Sebelum mengajukan surat PKL, mohon lengkapi data berikut pada halaman profil Anda: Nama Lengkap, Jenis Kelamin, Link CV, dan Link Portofolio."
        confirmText="Lengkapi Profil"
        cancelText="Nanti Saja"
        confirmAction="redirectToProfile" />

    <x-ui.confirmation
        :open="$confirmingAction === 'submission_pending'"
        type="danger"
        title="Pengajuan PKL Belum Disetujui"
        message="Surat PKL belum dapat dibuat karena pengajuan PKL Anda belum mendapat persetujuan dari guru. Silakan cek status pengajuan terlebih dahulu."
        confirmText="Cek Pengajuan PKL"
        cancelText="Nanti Saja"
        confirmAction="redirectToSubmission" />

    <x-ui.confirmation
        :open="$confirmingAction === 'already_generated'"
        type="danger"
        title="Surat Sudah Pernah Dibuat"
        message="Anda sudah pernah mengajukan surat PKL untuk perusahaan ini. Surat tidak dapat dibuat ulang untuk perusahaan yang sama."
        confirmText="Cek Pengajuan Surat"
        cancelText="Tutup"
        confirmAction="redirectToLetterCheck" />

</div>
