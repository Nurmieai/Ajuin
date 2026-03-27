{{-- resources/views/livewire/teacher/submission/detail.blade.php --}}
<div>
    <template x-teleport="body">
        <dialog
            id="teacher_submission_detail_modal"
            class="modal backdrop-blur-sm p-4"
            wire:ignore.self>

            {{-- Ditambahkan flex flex-col agar modal body mengisi ruang yang tepat dan scrollable --}}
            <div class="modal-box w-full max-w-2xl bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 shadow-xl rounded-xl p-0 overflow-hidden flex flex-col max-h-[90vh]">

                @if($selectedSubmission)

                {{-- Header --}}
                {{-- Penyesuaian padding (p-4 sm:p-6) dan ukuran teks judul --}}
                <div class="p-4 sm:p-6 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-900/50 shrink-0">
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 dark:text-slate-100">Detail Pengajuan PKL</h3>
                    <button onclick="document.getElementById('teacher_submission_detail_modal').close()" class="btn btn-sm btn-circle btn-ghost">✕</button>
                </div>

                {{-- Body (Scrollable) --}}
                {{-- Penyesuaian padding (p-4 sm:p-8) dan gap untuk mobile --}}
                <div class="p-4 sm:p-8 space-y-5 sm:space-y-6 overflow-y-auto flex-1">

                    {{-- Info Siswa --}}
                    <div class="bg-blue-50 dark:bg-blue-950/30 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
                        <label class="text-xs font-semibold uppercase tracking-wider text-blue-700 dark:text-blue-400">Nama Siswa</label>
                        <p class="mt-1 text-base sm:text-lg font-semibold text-blue-900 dark:text-blue-300 leading-tight">
                            {{ $selectedSubmission->user->fullname }}
                        </p>
                        <p class="text-sm text-blue-600 dark:text-blue-400 mt-0.5">
                            NISN: {{ $selectedSubmission->user->nisn }}
                        </p>
                    </div>

                    {{-- Info Perusahaan --}}
                    <div class="grid grid-cols-1 gap-4">
                        <div class="bg-slate-50 dark:bg-slate-900 p-4 rounded-lg">
                            <label class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Nama Perusahaan</label>
                            <p class="mt-1 text-base sm:text-lg font-medium text-slate-700 dark:text-slate-300 leading-tight break-words">
                                {{ $selectedSubmission->company_name }}
                            </p>
                        </div>

                        {{-- Ubah grid-cols-2 menjadi grid-cols-1 di mobile, dan grid-cols-2 di layar sm (tablet) ke atas --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Email Perusahaan</label>
                                {{-- Tambah break-words agar email yang panjang tidak membuat scroll horizontal di HP --}}
                                <p class="mt-1 text-slate-700 dark:text-slate-300 text-sm break-words">
                                    {{ $selectedSubmission->company_email ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">No. Telepon</label>
                                <p class="mt-1 text-slate-700 dark:text-slate-300 text-sm">
                                    {{ $selectedSubmission->company_phone_number ?? '-' }}
                                </p>
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Alamat Perusahaan</label>
                            <p class="mt-1 text-slate-700 dark:text-slate-300 text-sm leading-relaxed break-words">
                                {{ $selectedSubmission->company_address }}
                            </p>
                        </div>
                    </div>

                    {{-- Periode PKL --}}
                    <div class="rounded-lg border border-slate-200 dark:border-slate-800 bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800 p-4">
                        <label class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 block mb-2">Periode PKL</label>
                        {{-- Tambah flex-wrap agar tanggal turun ke bawah jika layar terlalu sempit --}}
                        <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-sm sm:text-base text-slate-700 dark:text-slate-300 font-medium">
                            <span>{{ \Carbon\Carbon::parse($selectedSubmission->start_date)->translatedFormat('d F Y') }}</span>
                            <span class="text-slate-400 hidden sm:inline-block">—</span>
                            <span class="text-slate-400 sm:hidden">s/d</span>
                            <span>{{ \Carbon\Carbon::parse($selectedSubmission->finish_date)->translatedFormat('d F Y') }}</span>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                            Durasi: {{ \Carbon\Carbon::parse($selectedSubmission->start_date)->diffInDays($selectedSubmission->finish_date) }} hari
                        </p>
                    </div>

                    {{-- Dokumen --}}
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 block mb-3">Dokumen Persyaratan</label>
                        <div class="grid grid-cols-1 gap-3">
                            @forelse ($selectedSubmission->certificates as $certificate)
                            {{-- Di HP, jika nama dokumen panjang, tombol bisa memakan tempat. Kita pastikan susunannya aman --}}
                            <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 gap-3">
                                <div class="flex items-center gap-3 overflow-hidden">
                                    <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded text-blue-600 shrink-0">
                                        <x-ui.icon name="document-text" size="sm" />
                                    </div>
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300 truncate">
                                        @switch($certificate->type)
                                        @case('industrial_visit') Kunjungan Industri @break
                                        @case('competency_test') Uji Kompetensi @break
                                        @case('spp_card') Kartu SPP @break
                                        @default {{ $certificate->type }}
                                        @endswitch
                                    </span>
                                </div>
                                <a href="{{ asset('storage/' . $certificate->file_path) }}" target="_blank" class="btn btn-sm btn-primary btn-outline shrink-0">Lihat</a>
                            </div>
                            @empty
                            <p class="text-sm text-slate-500 dark:text-slate-400 text-center py-4 italic">Tidak ada dokumen tersedia</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="p-4 sm:px-6 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-800 flex justify-end shrink-0">
                    {{-- Tombol selebar layar (w-full) di HP, kembali normal di Desktop (sm:w-auto) --}}
                    <button
                        onclick="document.getElementById('teacher_submission_detail_modal').close()"
                        class="btn w-full sm:w-auto px-8 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-200 border-none hover:bg-slate-300">
                        Tutup
                    </button>
                </div>

                @else
                {{-- Loader diletakkan di tengah sisa ruang --}}
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
        window.addEventListener('open-teacher-detail-modal', () => {
            document.getElementById('teacher_submission_detail_modal').showModal();
        });
    </script>
</div>