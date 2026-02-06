<x-slot:title>Form Mitra PKL</x-slot:title>

<div class="bg-white p-6 rounded shadow max-w-xl">
    <form wire:submit.prevent="save" class="space-y-4">

        <input wire:model="name" class="input input-bordered w-full"
            placeholder="Nama Mitra">

        <input wire:model="quota" type="number"
            class="input input-bordered w-full"
            placeholder="Jumlah Kuota">

        <input wire:model="criteria" class="input input-bordered w-full"
            placeholder="Kriteria">

        <textarea wire:model="address"
            class="textarea textarea-bordered w-full"
            placeholder="Lokasi"></textarea>

        <input wire:model="start_date" type="date"
            class="input input-bordered w-full">

        <input wire:model="finish_date" type="date"
            class="input input-bordered w-full">

        <button class="btn btn-primary w-full">
            Simpan
        </button>
    </form>
</div>