<x-slot:title>Form Mitra PKL</x-slot:title>

<div class="flex justify-center ">
    <div class="flex flex-col gap-4">

        <x-ui.breadcrumbs :items="[
            'Mitra' => [
                'url' => route('partners.index'),
                'icon' => 'academic-cap' 
            ], 
            ($partnerId ? 'Edit Mitra' : 'Tambah Mitra') => [
            'url' => null,
            'icon' => $partnerId ? 'pencil-square' : 'plus' 
        ],
        ]" />



        <div class="mb-6">
            <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100">
                Informasi Mitra
            </h2>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Lengkapi data kerjasama mitra industri PKL.
            </p>
        </div>

        <form wire:submit.prevent="save" class="space-y-5">

            {{-- Informasi Dasar --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui.input
                    name="name"
                    label="Nama Mitra"
                    placeholder="PT. Teknologi Nusantara" />

                <x-ui.input
                    name="email"
                    type="email"
                    label="Email"
                    placeholder="kontak@mitra.com" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui.input
                    name="phone_number"
                    label="No. Telepon"
                    placeholder="0812..." />

                <x-ui.input
                    name="quota"
                    type="number"
                    label="Kuota"
                    placeholder="10" />
            </div>

            {{-- Kriteria & Jurusan --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui.input
                    name="criteria"
                    label="Kriteria"
                    placeholder="Contoh: Disiplin, Paham Laravel" />

                <x-ui.input
                    name="selectedMajors"
                    label="Jurusan"
                    type="select"
                    :options="$allMajors->pluck('name','id')"
                    multiple
                    class="min-h-[100px]" />
            </div>

            {{-- Alamat --}}
            <x-ui.input
                name="address"
                type="textarea"
                label="Lokasi / Alamat"
                placeholder="Alamat lengkap mitra..."
                class="min-h-[90px]" />

            {{-- Periode --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui.input
                    name="start_date"
                    type="date"
                    label="Mulai Kerjasama" />

                <x-ui.input
                    name="finish_date"
                    type="date"
                    label="Berakhir Kerjasama" />
            </div>

            {{-- Tombol --}}
            <div class="flex gap-3 pt-3 border-t border-slate-200 dark:border-slate-800">
                <a href="{{ route('partners.index') }}"
                    wire:navigate
                    class="btn btn-ghost flex-1 text-slate-700 dark:text-slate-300">
                    Batal
                </a>

                <button
                    class="btn flex-1 text-white border-none
                   bg-blue-600 hover:bg-blue-700
                   dark:bg-blue-500 dark:hover:bg-blue-400">

                    <span wire:loading.remove>Simpan Data</span>
                    <span wire:loading class="loading loading-spinner"></span>
                </button>
            </div>

        </form>
    </div>
</div>