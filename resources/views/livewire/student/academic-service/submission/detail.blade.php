{{-- resources/views/livewire/student/academic-service/submission/detail.blade.php --}}
<div>
    <template x-teleport="body">
        <dialog
            id="detail_submission_modal"
            class="modal backdrop-blur-sm"
            wire:ignore.self>

            <div class="modal-box w-full max-w-2xl bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 shadow-xl rounded-xl p-0 overflow-hidden">

                @if($selectedSubmission)
                {{-- Header --}}
                <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-900/50">
                    <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100">Detail Pengajuan PKL</h3>
                    <button onclick="document.getElementById('detail_submission_modal').close()" class="btn btn-sm btn-circle btn-ghost">✕</button>
                </div>

                {{-- Body (Dikembalikan ke Style Awal dengan penyesuaian kontainer) --}}
                <div class="p-8 space-y-6 max-h-[70vh] overflow-y-auto">

                    {{-- Info Siswa --}}
                    <div class="bg-blue-50 dark:bg-blue-950/30 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
                        <label class="text-xs font-semibold uppercase tracking-wider text-blue-700 dark:text-blue-400">
                            Nama Siswa
                        </label>
                        <p class="mt-1 text-lg font-semibold text-blue-900 dark:text-blue-300">
                            {{ $selectedSubmission->user->fullname }}
                        </p>
                        <p class="text-sm text-blue-600 dark:text-blue-400">
                            NISN: {{ $selectedSubmission->user->nisn }}
                        </p>
                    </div>

                    {{-- Info Perusahaan --}}
                    <div class="grid grid-cols-1 gap-4">
                        <div class="bg-slate-50 dark:bg-slate-900 p-4 rounded-lg">
                            <label class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Nama Perusahaan
                            </label>
                            <p class="mt-1 text-lg font-medium text-slate-700 dark:text-slate-300">
                                {{ $selectedSubmission->company_name }}
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                    Email Perusahaan
                                </label>
                                <p class="mt-1 text-slate-700 dark:text-slate-300">
                                    {{ $selectedSubmission->company_email ?? '-' }}
                                </p>
                            </div>

                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    No. Telepon
                                </label>
                                <p class="mt-1 text-slate-700 dark:text-slate-300">
                                    {{ $selectedSubmission->company_phone_number ?? '-' }}
                                </p>
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Alamat Perusahaan
                            </label>
                            <p class="mt-1 text-slate-700 dark:text-slate-300 leading-relaxed">
                                {{ $selectedSubmission->company_address }}
                            </p>
                        </div>
                    </div>

                    {{-- Periode PKL --}}
                    <div class="rounded-lg border border-slate-200 dark:border-slate-800 bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800 p-4">
                        <label class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 block mb-2">
                            Periode PKL
                        </label>
                        <div class="flex items-center gap-3 text-slate-700 dark:text-slate-300">
                            <span class="font-semibold">
                                {{ \Carbon\Carbon::parse($selectedSubmission->start_date)->translatedFormat('d F Y') }}
                            </span>
                            <span class="text-slate-400">—</span>
                            <span class="font-semibold">
                                {{ \Carbon\Carbon::parse($selectedSubmission->finish_date)->translatedFormat('d F Y') }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                            Durasi: {{ \Carbon\Carbon::parse($selectedSubmission->start_date)->diffInDays($selectedSubmission->finish_date) }} hari
                        </p>
                    </div>

                    {{-- Dokumen --}}
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 block mb-3">
                            Dokumen Persyaratan
                        </label>
                        <div class="grid grid-cols-1 gap-3">
                            @forelse ($selectedSubmission->certificates as $certificate)
                            <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded text-blue-600">
                                        <x-ui.icon name="document-text" size="sm" />
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                            @switch($certificate->type)
                                            @case('industrial_visit') Kunjungan Industri @break
                                            @case('competency_test') Uji Kompetensi @break
                                            @case('spp_card') Kartu SPP @break
                                            @default {{ $certificate->type }}
                                            @endswitch
                                        </p>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/' . $certificate->file_path) }}"
                                    target="_blank"
                                    class="btn btn-sm btn-primary btn-outline">
                                    Lihat
                                </a>
                            </div>
                            @empty
                            <p class="text-sm text-slate-500 dark:text-slate-400 text-center py-4 italic">
                                Tidak ada dokumen tersedia
                            </p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="p-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-800 text-right">
                    <button onclick="document.getElementById('detail_submission_modal').close()" class="btn px-8 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-200 border-none hover:bg-slate-300">
                        Tutup
                    </button>
                </div>
                @else
                {{-- Loader --}}
                <div class="p-20 flex flex-col items-center justify-center gap-4">
                    <span class="loading loading-spinner loading-lg text-blue-600"></span>
                    <p class="text-slate-400 animate-pulse text-sm font-medium">Memuat data pengajuan...</p>
                </div>
                @endif
            </div>

            <form method="dialog" class="modal-backdrop">
                <button wire:click="closeDetail">close</button>
            </form>
        </dialog>
    </template>

    <script>
        window.addEventListener('open-detail-modal', () => {
            const modal = document.getElementById('detail_submission_modal');
            if (modal) modal.showModal();
        });
    </script>
</div>