<x-slot:title>
    Pengajuan PKL
</x-slot:title>

<div class="flex flex-col gap-4">
    <x-ui.breadcrumbs :items="[
        'Buat Pengajuan' => [
            'url' => route('student.submission-create'),
            'icon' => 'edit' 
        ],
    ]" />

    {{-- Info Siswa (Disabled) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-slate-50 dark:bg-slate-900/50 p-4 rounded-lg border border-slate-200 dark:border-slate-800">
        <x-ui.input
            name="fullname"
            label="Nama Lengkap"
            value="{{ auth()->user()->fullname }}"
            disabled />

        <x-ui.input
            name="nisn"
            label="NISN"
            value="{{ auth()->user()->nisn }}"
            disabled />

        <x-ui.input
            name="major_id"
            label="Jurusan"
            value="{{ auth()->user()->major?->name }}"
            disabled />
    </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 text-center">
                * Kamu hanya bisa memiliki 3 Pengajuan Mandiri yang aktif!
            </p>

    <form wire:submit.prevent="create" class="space-y-4">
        {{-- Data Perusahaan --}}
        <div class="space-y-4">
            <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200 border-b border-slate-200 dark:border-slate-700 pb-2">
                Data Perusahaan
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui.input
                    wire:model="company_name"
                    name="company_name"
                    label="Nama Perusahaan *"
                    placeholder="Masukkan nama perusahaan" />

                <x-ui.input
                    wire:model="company_email"
                    name="company_email"
                    type="email"
                    label="Email Perusahaan *"
                    placeholder="email@perusahaan.com" />
            </div>

            <x-ui.input
                wire:model="company_phone_number"
                name="company_phone_number"
                label="Nomor Telepon Perusahaan *"
                placeholder="08xx-xxxx-xxxx" />

            <x-ui.input
                wire:model="company_address"
                name="company_address"
                type="textarea"
                label="Alamat Perusahaan *"
                placeholder="Masukkan alamat lengkap perusahaan" />
        </div>

        {{-- Dokumen Persyaratan --}}
        <div class="space-y-4">
            <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200 border-b border-slate-200 dark:border-slate-700 pb-2">
                Dokumen Persyaratan
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <x-ui.input
                        wire:model="industrial_visit"
                        name="industrial_visit"
                        label="Sertifikat Kunjungan Industri *"
                        type="file" />
                    <div wire:loading wire:target="industrial_visit" class="text-sm text-blue-500 mt-1 flex items-center gap-1">
                        <span class="loading loading-spinner loading-xs"></span>
                        Mengupload...
                    </div>
                </div>

                <div>
                    <x-ui.input
                        wire:model="competency_test"
                        name="competency_test"
                        label="Sertifikat Uji Kompetensi *"
                        type="file" />
                    <div wire:loading wire:target="competency_test" class="text-sm text-blue-500 mt-1 flex items-center gap-1">
                        <span class="loading loading-spinner loading-xs"></span>
                        Mengupload...
                    </div>
                </div>

                <div>
                    <x-ui.input
                        wire:model="spp_card"
                        name="spp_card"
                        label="Kartu SPP *"
                        type="file" />
                    <div wire:loading wire:target="spp_card" class="text-sm text-blue-500 mt-1 flex items-center gap-1">
                        <span class="loading loading-spinner loading-xs"></span>
                        Mengupload...
                    </div>
                </div>
            </div>
        </div>

        {{-- Periode PKL --}}
        <div class="space-y-4">
            <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200 border-b border-slate-200 dark:border-slate-700 pb-2">
                Periode PKL
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui.input
                    wire:model="start_date"
                    name="start_date"
                    label="Tanggal Mulai *"
                    type="date" />

                <x-ui.input
                    wire:model="finish_date"
                    name="finish_date"
                    label="Tanggal Selesai *"
                    type="date" />
            </div>
        </div>

        {{-- Tombol Submit --}}
        <div class="pt-4 border-t border-slate-200 dark:border-slate-700">
            <button
                type="submit"
                class="w-full py-3 rounded-lg bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-semibold transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl"
                wire:loading.attr="disabled"
                wire:target="create">

                <span wire:loading.remove wire:target="create" class="flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Buat Pengajuan
                </span>

                <span wire:loading wire:target="create" class="flex items-center justify-center gap-2">
                    <span class="loading loading-spinner loading-sm"></span>
                    Memproses...
                </span>
            </button>

            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 text-center">
                * Pastikan semua data dan dokumen sudah benar sebelum mengirim
            </p>
        </div>
    </form>
</div>