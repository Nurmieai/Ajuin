<x-slot:title>
    Profil
</x-slot:title>

<div class="flex flex-col gap-6">

    <x-ui.breadcrumbs :items="[
        'Profil' => [
            'url' => route('student.profile'),
            'icon' => 'user'
        ],
    ]" />

    <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Kolom Kiri -->
        <div class="flex flex-col gap-4">

            <x-ui.input
            wire:model="fullname"
            name="fullname"
            label="Nama Lengkap"
            />

            <x-ui.input
                wire:model="nisn"
                name="nisn"
                label="NISN"
                disabled
            />

            <x-ui.input
                name="major"
                label="Jurusan"
                value="{{ auth()->user()->major?->name }}"
                disabled
            />

            <x-ui.select
                wire:model="gender"
                label="Jenis Kelamin"
                :options="[
                    'L' => 'Laki-laki',
                    'P' => 'Perempuan'
                ]"
            />

        </div>

        <!-- Kolom Kanan -->
        <div class="flex flex-col gap-4">

            <x-ui.input
                wire:model="birth_date"
                label="Tanggal Lahir"
                type="date"
            />

            <x-ui.input
                wire:model="nomor_handphone"
                label="Nomor Handphone"
            />

            <x-ui.input
                wire:model="alamat_tinggal"
                label="Alamat Tinggal Saat Ini"
                type="textarea"
            />

            <x-ui.input
                wire:model="nama_tempat_pkl"
                name="nama_tempat_pkl"
                label="Nama Tempat PKL"
                disabled
            />

        </div>

        <!-- Tombol -->
        <div class="md:col-span-2">
            <button
                type="submit"
                class="w-full mt-4 py-3 rounded-md bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition"
                wire:loading.attr="disabled">
                Simpan Perubahan
            </button>
        </div>

    </form>

    <x-ui.toast />

</div>
