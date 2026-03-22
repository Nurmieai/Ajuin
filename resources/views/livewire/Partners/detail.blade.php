<div>
    {{-- Teleport modal ke body agar menutupi seluruh layar --}}
    <template x-teleport="body">
        <dialog
            id="detail_partner_modal"
            class="modal backdrop-blur-sm"
            wire:ignore.self>

            <div class="modal-box w-full max-w-xl bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 shadow-xl rounded-xl p-0 overflow-hidden">

                @if($partner)
                {{-- Header --}}
                <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-900/50">
                    <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100">Detail Mitra PKL</h3>
                    <button onclick="document.getElementById('detail_partner_modal').close()" class="btn btn-sm btn-circle btn-ghost">✕</button>
                </div>

                {{-- Body --}}
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Kolom 1: Informasi Dasar & Kontak --}}
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wider text-slate-400">Nama Mitra</label>
                                <p class="mt-1 text-lg font-bold text-blue-600 dark:text-blue-400">{{ $partner->name }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wider text-slate-400">Email Kontak</label>
                                <p class="mt-1 text-slate-700 dark:text-slate-300">{{ $partner->email ?? 'Tidak ada email' }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wider text-slate-400">No. Telepon</label>
                                <p class="mt-1 text-slate-700 dark:text-slate-300">{{ $partner->phone_number ?? '-' }}</p>
                            </div>
                        </div>

                        {{-- Kolom 2: Detail Kerjasama PKL --}}
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wider text-slate-400">Kuota Tersedia</label>
                                <p class="mt-1 text-slate-700 dark:text-slate-300 font-medium">{{ $partner->quota }} Siswa</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wider text-slate-400">Jurusan Tersedia</label>
                                <p class="mt-1 text-slate-700 dark:text-slate-300">
                                    {{-- Perbaikan Bug JSON: Ambil namanya saja lalu pisahkan dengan koma --}}
                                    {{ $partner->majors && $partner->majors->isNotEmpty() ? $partner->majors->pluck('name')->join(', ') : 'Tidak ada jurusan tersedia' }}
                                </p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wider text-slate-400">Kriteria</label>
                                <p class="mt-1 text-slate-700 dark:text-slate-300">{{ $partner->criteria ?? 'Tidak ada kriteria' }}</p>
                            </div>
                        </div>

                    </div>

                    <div class="divider my-0"></div>

                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider text-slate-400">Alamat Lengkap</label>
                        <p class="mt-1 text-slate-700 dark:text-slate-300 leading-relaxed italic">"{{ $partner->address }}"</p>
                    </div>

                    <div class="p-4 rounded-xl border border-blue-100 dark:border-blue-900/30 bg-blue-50/50 dark:bg-blue-950/20">
                        <label class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase">Periode Kerjasama</label>
                        <div class="flex items-center gap-2 mt-1 text-sm text-slate-600 dark:text-slate-300">
                            <span class="font-semibold">{{ \Carbon\Carbon::parse($partner->start_date)->translatedFormat('d M Y') }}</span>
                            <span>s/d</span>
                            <span class="font-semibold">{{ \Carbon\Carbon::parse($partner->finish_date)->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="p-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-800 text-right">
                    <button onclick="document.getElementById('detail_partner_modal').close()" class="btn px-8 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-200 border-none hover:bg-slate-300">
                        Tutup
                    </button>
                </div>
                @else
                {{-- Loader sederhana saat data sedang diambil --}}
                <div class="p-20 flex justify-center">
                    <span class="loading loading-spinner loading-lg text-blue-600"></span>
                </div>
                @endif
            </div>

            <form method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
        </dialog>
    </template>

    {{-- Script untuk memicu modal --}}
    <script>
        window.addEventListener('open-detail-modal', () => {
            // Gunakan document.getElementById karena elemen sudah di-teleport ke body
            document.getElementById('detail_partner_modal').showModal();
        });
    </script>
</div>