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

    <x-ui.input name="fullname" label="Nama Lengkap" value="{{ auth()->user()->fullname }}" disabled />
    <x-ui.input name="nisn" label="NISN" value="{{ auth()->user()->nisn }}" disabled />
    <x-ui.input name="major_id" label="Jurusan" value="{{ auth()->user()->major?->name }}" disabled />

    <form wire:submit.prevent="create">
        @csrf
        <x-ui.input wire:model='company_name' name="company_name" label="Nama perusahaan" placeholder="Nama perusahaan" />

        <x-ui.input wire:model='company_email' name="company_email" type="email" label="Email perusahaan" placeholder="Email perusahaan" />

        <x-ui.input wire:model='company_phone_number' name="company_phone_number" label="Nomor Telepon perusahaan" placeholder="" />

        <x-ui.input wire:model='company_address' class="max-h-32" name="company_address" type="textarea" label="Alamat perusahaan" placeholder="Alamat perusahaan" />

        <div class="grid grid-cols-3 gap-4">
            <div>
                <x-ui.input wire:model='industrial_visit' name="industrial_visit" label="Kunjungan Industri" type="file" />
                <div wire:loading wire:target="industrial_visit" class="text-sm text-gray-500">Uploading...</div>
            </div>

            <div>
                <x-ui.input wire:model='competency_test' name="competency_test" label="Uji Kompetensi" type="file" />
                <div wire:loading wire:target="competency_test" class="text-sm text-gray-500">Uploading...</div>
            </div>

            <div>
                <x-ui.input wire:model='spp_card' name="spp_card" label="Kartu SPP" type="file" />
                <div wire:loading wire:target="spp_card" class="text-sm text-gray-500">Uploading...</div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-ui.input wire:model='start_date' name="start_date" label="Tanggal Mulai" type="date" />
            </div>

            <div>
                <x-ui.input wire:model='finish_date' name="finish_date" label="Tanggal Selesai" type="date" />
            </div>
        </div>

        <button
            type="submit"
            class="w-full mt-5 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition disabled:opacity-50"
            wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="create">Buat Pengajuan</span>
            <span wire:loading wire:target="create" class="loading loading-spinner loading-xs"></span>
        </button>
    </form>

    <x-ui.toast />
</div>