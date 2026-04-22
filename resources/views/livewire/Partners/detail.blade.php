<div>
    <template x-teleport="body">
        <dialog
            id="detail_partner_modal"
            class="modal backdrop-blur-sm p-4"
            wire:ignore.self>

            <div class="modal-box w-full max-w-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 shadow-xl rounded-xl p-0 overflow-hidden max-h-[90vh] flex flex-col">

                @if($partner)
                <div class="p-4 sm:p-6 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-900/50 shrink-0">
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 dark:text-slate-100">Detail Mitra PKL</h3>
                    <button onclick="document.getElementById('detail_partner_modal').close()" class="btn btn-sm btn-circle btn-ghost">✕</button>
                </div>

                <div class="p-4 sm:p-6 space-y-5 sm:space-y-6 overflow-y-auto flex-1">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">

                        <div class="space-y-4">
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wider text-slate-400">Nama Mitra</label>
                                <p class="mt-1 text-base sm:text-lg font-bold text-blue-600 dark:text-blue-400 leading-tight">{{ $partner->name }}</p>
                            </div>

                            {{-- Section Rating Rata-rata --}}
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wider text-slate-400">Rating Siswa</label>
                                <div class="flex items-center gap-2 mt-1">
                                    <div class="flex text-yellow-500">
                                        @for($i = 1; $i
                                        <= 5; $i++)
                                            <x-ui.icon name="star" size="sm" class="{{ $i <= round($averageRating) ? 'fill-yellow-500' : 'text-slate-300 dark:text-slate-700' }}" />
                                        @endfor
                                    </div>
                                    <span class="text-sm font-bold text-slate-700 dark:text-slate-200">({{ number_format($averageRating, 1) }})</span>
                                </div>
                            </div>

                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wider text-slate-400">Email Kontak</label>
                                <p class="mt-1 text-sm sm:text-base text-slate-700 dark:text-slate-300 break-words">{{ $partner->email ?? 'Tidak ada email' }}</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wider text-slate-400">Kuota & Jurusan</label>
                                <p class="mt-1 text-sm sm:text-base text-slate-700 dark:text-slate-300 font-medium">{{ $partner->quota }} Siswa</p>
                                <p class="text-xs text-slate-500 italic">
                                    {{ $partner->majors->pluck('name')->join(', ') }}
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
                        <div class="flex flex-wrap items-center gap-1 sm:gap-2 mt-1 text-sm text-slate-600 dark:text-slate-300">
                            <span class="font-semibold whitespace-nowrap">{{ \Carbon\Carbon::parse($partner->start_date)->translatedFormat('d M Y') }}</span>
                            <span class="text-xs sm:text-sm">s/d</span>
                            <span class="font-semibold whitespace-nowrap">{{ \Carbon\Carbon::parse($partner->finish_date)->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>

                    {{-- Section 3 Review Terbaru --}}
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider text-slate-400 block mb-3">Ulasan Terbaru dari Siswa</label>

                        @if(count($latestReviews) > 0)
                        <div class="space-y-3">
                            @foreach($latestReviews as $review)
                            <div class="p-3 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                                <div class="flex justify-between items-start mb-1">
                                    <span class="text-sm font-bold text-slate-800 dark:text-slate-100">{{ $review->student->name ?? 'Siswa' }}</span>
                                    <div class="flex items-center gap-1">
                                        <x-ui.icon name="star" size="xs" class="text-yellow-500 fill-yellow-500" />
                                        <span class="text-xs font-medium">{{ $review->rating }}</span>
                                    </div>
                                </div>
                                <h4 class="text-xs font-semibold text-blue-600 dark:text-blue-400 mb-1">{{ $review->judul }}</h4>
                                <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-2">"{{ $review->isi }}"</p>
                                <div class="review-item">
                                    <p>{{ $review->content }}</p>

                                    <span class="text-[10px] text-slate-400 mt-2 block">
                                        {{ $review->created_at->format('d M Y') }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="py-4 text-center border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-xl">
                            <p class="text-sm text-slate-500 italic">Belum ada ulasan untuk mitra ini.</p>
                        </div>
                        @endif
                    </div>

                </div>

                <div class="p-4 sm:px-6 bg-slate-100 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-800 shrink-0 flex justify-end">
                    <button onclick="document.getElementById('detail_partner_modal').close()" class="btn w-full sm:w-auto px-8 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-200 border-none hover:bg-slate-300">
                        Tutup
                    </button>
                </div>
                @else
                <div class="p-20 flex justify-center items-center flex-1">
                    <span class="loading loading-spinner loading-lg text-blue-600"></span>
                </div>
                @endif
            </div>

            <form method="dialog" class="modal-backdrop">
                <button wire:click="resetData">close</button>
            </form>
        </dialog>
    </template>

    <script>
        window.addEventListener('open-detail-modal', () => {
            document.getElementById('detail_partner_modal').showModal();
        });
    </script>
</div>