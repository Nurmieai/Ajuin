<x-slot:title>
    Edit Pengajuan PKL
</x-slot:title>

<div class="flex flex-col gap-4">
    <x-ui.breadcrumbs :items="[
        'Layanan Akademik' => [
            'url' => route('student.academic-service'),
            'icon' => 'academic-cap'
        ],
        'Kelola Pengajuan' => [
            'url' => route('student.submission-manage'),
            'icon' => 'clipboard-document-list' 
        ],
        'Edit Pengajuan' => [
            'url' => '#',
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

    <form wire:submit.prevent="update" class="space-y-6">
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
            <div class="flex flex-col">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200 border-b border-slate-200 dark:border-slate-700 pb-2">
                    Dokumen Persyaratan
                </h3>
                <p class="text-xs text-slate-500 mt-1 italic">* Kosongkan jika tidak ingin mengganti file yang sudah ada</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Kunjungan Industri --}}
                <div class="space-y-2">
                    @if($existing_industrial_visit)
                    <div class="flex items-center gap-2 p-2 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md mb-2">
                        <x-ui.icon name="document-check" size="sm" class="text-blue-600" />
                        <a href="{{ asset('storage/' . $existing_industrial_visit->file_path) }}" target="_blank" class="text-xs font-medium text-blue-700 dark:text-blue-400 hover:underline truncate">
                            Lihat File Saat Ini
                        </a>
                    </div>
                    @endif
                    <x-ui.input
                        wire:model="industrial_visit"
                        name="industrial_visit"
                        label="Ganti Sertifikat KI"
                        type="file" />
                    <div wire:loading wire:target="industrial_visit" class="text-xs text-blue-500 mt-1 flex items-center gap-1">
                        <span class="loading loading-spinner loading-xs"></span> Mengupload...
                    </div>
                </div>

                {{-- Uji Kompetensi --}}
                <div class="space-y-2">
                    @if($existing_competency_test)
                    <div class="flex items-center gap-2 p-2 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md mb-2">
                        <x-ui.icon name="document-check" size="sm" class="text-blue-600" />
                        <a href="{{ asset('storage/' . $existing_competency_test->file_path) }}" target="_blank" class="text-xs font-medium text-blue-700 dark:text-blue-400 hover:underline truncate">
                            Lihat File Saat Ini
                        </a>
                    </div>
                    @endif
                    <x-ui.input
                        wire:model="competency_test"
                        name="competency_test"
                        label="Ganti Sertifikat UK"
                        type="file" />
                    <div wire:loading wire:target="competency_test" class="text-xs text-blue-500 mt-1 flex items-center gap-1">
                        <span class="loading loading-spinner loading-xs"></span> Mengupload...
                    </div>
                </div>

                {{-- Kartu SPP --}}
                <div class="space-y-2">
                    @if($existing_spp_card)
                    <div class="flex items-center gap-2 p-2 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md mb-2">
                        <x-ui.icon name="document-check" size="sm" class="text-blue-600" />
                        <a href="{{ asset('storage/' . $existing_spp_card->file_path) }}" target="_blank" class="text-xs font-medium text-blue-700 dark:text-blue-400 hover:underline truncate">
                            Lihat File Saat Ini
                        </a>
                    </div>
                    @endif
                    <x-ui.input
                        wire:model="spp_card"
                        name="spp_card"
                        label="Ganti Kartu SPP"
                        type="file" />
                    <div wire:loading wire:target="spp_card" class="text-xs text-blue-500 mt-1 flex items-center gap-1">
                        <span class="loading loading-spinner loading-xs"></span> Mengupload...
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
        <div class="pt-6 border-t border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row gap-3">
            <button
                type="button"
                wire:click="cancel"
                class="w-[48px] btn btn-ghost flex-1 order-2 sm:order-1 ">
                Batal
            </button>

            <button
                type="submit"
                class="flex-1 py-3 rounded-lg bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-semibold transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl order-1 sm:order-2"
                wire:loading.attr="disabled"
                wire:target="update">

                <span wire:loading.remove wire:target="update" class="flex items-center justify-center gap-2">
                    <x-ui.icon name="check-circle" size="sm" />
                    Simpan Perubahan
                </span>

                <span wire:loading wire:target="update" class="flex items-center justify-center gap-2">
                    <span class="loading loading-spinner loading-sm"></span>
                    Menyimpan...
                </span>
            </button>
        </div>
    </form>
</div>