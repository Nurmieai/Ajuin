<x-slot:title>
    Profil 
</x-slot:title>

<div class="flex flex-col gap-4">

    <x-ui.breadcrumbs :items="[
        'Profil' => [
            'url' => route('student.profile'),
            'icon' => 'user'
        ],
    ]" />

    <form wire:submit.prevent="save">

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

        <x-ui.input
            wire:model="class"
            name="class"
            label="Kelas"
        />

        <x-ui.input
            wire:model="phone"
            name="phone"
            label="No. Telepon"
        />

        <x-ui.input
            wire:model="address"
            name="address"
            label="Alamat"
            type="textarea"
        />

        <button
            type="submit"
            class="w-full mt-5 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition"
            wire:loading.attr="disabled">
            Simpan Perubahan
        </button>

    </form>

    <x-ui.toast />

</div>
