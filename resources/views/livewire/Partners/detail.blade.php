<div>
    {{-- Teleport modal ke body agar menutupi seluruh layar --}}
    <template x-teleport="body">
        <dialog
            id="detail_partner_modal"
            class="modal backdrop-blur-sm p-4"
            wire:ignore.self>

            {{-- Ditambahkan flex flex-col agar header & footer menempel dengan rapi saat di-scroll di mobile --}}
            <div class="modal-box w-full max-w-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 shadow-xl rounded-xl p-0 overflow-hidden max-h-[90vh] flex flex-col">

                @if($partner)
                {{-- Header (Ditambahkan shrink-0 dan padding dinamis) --}}
                <div class="p-4 sm:p-6 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-900/50 shrink-0">
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 dark:text-slate-100">Detail Mitra PKL</h3>
                    <button onclick="document.getElementById('detail_partner_modal').close()" class="btn btn-sm btn-circle btn-ghost">✕</button>
                </div>

                {{-- Body (Ditambahkan flex-1 dan padding dinamis) --}}
                <div class="p-4 sm:p-6 space-y-5 sm:space-y-6 overflow-y-auto flex-1">
                    {{-- Grid otomatis 1 kolom di HP, 2 kolom di tablet/desktop --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">

                        {{-- Kolom 1: Informasi Dasar & Kontak --}}
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wider text-slate-400">Nama Mitra</label>
                                <p class="mt-1 text-base sm:text-lg font-bold text-blue-600 dark:text-blue-400 leading-tight">{{ $partner->name }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wider text-slate-400">Email Kontak</label>
                                {{-- Ditambahkan break-words agar email panjang tidak merusak layout HP --}}
                                <p class="mt-1 text-sm sm:text-base text-slate-700 dark:text-slate-300 break-words">{{ $partner->email ?? 'Tidak ada email' }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wider text-slate-400">No. Telepon</label>
                                <p class="mt-1 text-sm sm:text-base text-slate-700 dark:text-slate-300">{{ $partner->phone_number ?? '-' }}</p>
                            </div>
                        </div>

                        {{-- Kolom 2: Detail Kerjasama PKL --}}
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wider text-slate-400">Kuota Tersedia</label>
                                <p class="mt-1 text-sm sm:text-base text-slate-700 dark:text-slate-300 font-medium">{{ $partner->quota }} Siswa</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wider text-slate-400">Jurusan Tersedia</label>
                                <p class="mt-1 text-sm sm:text-base text-slate-700 dark:text-slate-300 leading-relaxed">
                                    {{ $partner->majors && $partner->majors->isNotEmpty() ? $partner->majors->pluck('name')->join(', ') : 'Tidak ada jurusan tersedia' }}
                                </p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wider text-slate-400">Kriteria</label>
                                <p class="mt-1 text-sm sm:text-base text-slate-700 dark:text-slate-300 leading-relaxed">{{ $partner->criteria ?? 'Tidak ada kriteria' }}</p>
                            </div>
                        </div>

                    </div>

                    <div class="divider my-0 opacity-50"></div>

                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider text-slate-400">Alamat Lengkap</label>
                        {{-- Ditambahkan break-words --}}
                        <p class="mt-1 text-sm sm:text-base text-slate-700 dark:text-slate-300 leading-relaxed italic break-words">"{{ $partner->address }}"</p>
                    </div>

                    <div class="p-3 sm:p-4 rounded-xl border border-blue-100 dark:border-blue-900/30 bg-blue-50/50 dark:bg-blue-950/20">
                        <label class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase">Periode Kerjasama</label>
                        {{-- Ditambahkan flex-wrap agar tanggal tidak terjepit di layar kecil --}}
                        <div class="flex flex-wrap items-center gap-1 sm:gap-2 mt-1 text-sm text-slate-600 dark:text-slate-300">
                            <span class="font-semibold whitespace-nowrap">{{ \Carbon\Carbon::parse($partner->start_date)->translatedFormat('d M Y') }}</span>
                            <span class="text-xs sm:text-sm">s/d</span>
                            <span class="font-semibold whitespace-nowrap">{{ \Carbon\Carbon::parse($partner->finish_date)->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="p-4 sm:px-6 bg-slate-100 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-800 shrink-0 flex justify-end">
                    {{-- Tombol selebar layar di HP (w-full), tapi kembali normal di Desktop (sm:w-auto) --}}
                    <button onclick="document.getElementById('detail_partner_modal').close()" class="btn w-full sm:w-auto px-8 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-200 border-none hover:bg-slate-300">
                        Tutup
                    </button>
                </div>
                @else
                {{-- Loader diletakkan di tengah sisa ruang (flex-1) --}}
                <div class="p-20 flex justify-center items-center flex-1">
                    <span class="loading loading-spinner loading-lg text-blue-600"></span>
                </div>
                @endif
            </div>

            <form method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
        </dialog>
    </template>

    <script>
        window.addEventListener('open-detail-modal', () => {
            document.getElementById('detail_partner_modal').showModal();
        });
    </script>
</div>