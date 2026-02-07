<div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-xl">

        <h2 class="text-xl font-bold mb-6 border-b pb-2 text-gray-700">Detail Mitra PKL</h2>

        <div class="space-y-4">
            <!-- Nama Mitra (Highlight) -->
            <div>
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Mitra</label>
                <p class="text-lg font-medium text-blue-600">{{ $partner->name }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <!-- Kontak & Email -->
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</label>
                    <p class="text-gray-700">{{ $partner->email ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Telepon</label>
                    <p class="text-gray-700">{{ $partner->phone_number ?? '-' }}</p>
                </div>

                <!-- Kuota & Kriteria -->
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kuota</label>
                    <p class="text-gray-700">{{ $partner->quota }} Orang</p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kriteria</label>
                    <p class="text-gray-700">{{ $partner->criteria ?? '-' }}</p>
                </div>
            </div>

            <!-- Alamat -->
            <div>
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Alamat</label>
                <p class="text-gray-700 leading-relaxed">{{ $partner->address }}</p>
            </div>

            <!-- Periode -->
            <div class="bg-gray-50 p-3 rounded-md border border-gray-100">
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Periode Kerjasama</label>
                <p class="text-sm text-gray-600">
                    <span class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($partner->start_date)->translatedFormat('d F Y') }}</span>
                    s/d
                    <span class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($partner->finish_date)->translatedFormat('d F Y') }}</span>
                </p>
            </div>
        </div>

        <div class="mt-8 pt-4 border-t text-right">
            <button wire:click="close" class="px-5 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-md transition-colors font-medium">
                Tutup
            </button>
        </div>
    </div>
</div>