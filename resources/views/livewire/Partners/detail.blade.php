<div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded w-full max-w-xl">

        <h2 class="text-lg font-bold mb-4">Detail Mitra PKL</h2>

        <x-ui.table
            :columns="['Field', 'Value']"
            :rows="[
                ['Nama Mitra', $partner->name],
                ['Jumlah Kuota', $partner->quota],
                ['Kriteria', $partner->criteria ?? '-'],
                ['Jurusan', 'RPL / TKJ'],
                ['Lokasi', $partner->address],
                ['Fasilitas', '-'],
                ['Tanggal Mulai', $partner->start_date],
                ['Tanggal Selesai', $partner->finish_date],
            ]" />

        <div class="mt-4 text-right">
            <button wire:click="close" class="btn btn-sm">
                Tutup
            </button>
        </div>
    </div>
</div>