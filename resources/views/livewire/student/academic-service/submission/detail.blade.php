<div class="bg-white dark:bg-slate-950 p-8 rounded-xl shadow-xl
            w-full max-w-3xl border border-slate-200 dark:border-slate-800
            max-h-[90vh] overflow-y-auto"
     wire:click.stop>

    <div class="flex justify-between items-start mb-6 border-b border-slate-200 dark:border-slate-800 pb-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100">
                Detail Pengajuan PKL
            </h2>
            <span class="badge {{ $selectedSubmission->getStatusBadgeClass() }} mt-2">{{ $selectedSubmission->getStatusLabel() }}</span>
        </div>
        <button 
            wire:click="closeDetail"
            class="btn btn-sm btn-circle btn-ghost">
            X
        </button>
    </div>

    <div class="space-y-6">
        <div class="grid grid-cols-1 gap-4">
            <div class="bg-slate-50 dark:bg-slate-900 p-4 rounded-lg">
                <label class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Nama Perusahaan</label>
                <p class="mt-1 text-lg font-medium text-slate-700 dark:text-slate-300">{{ $selectedSubmission->company_name }}</p>
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
                    <label class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
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
                <p class="mt-1 text-slate-700 dark:text-slate-300">
                    {{ $selectedSubmission->company_address }}
                </p>
            </div>
        </div>

        <div class="rounded-lg border border-slate-200 dark:border-slate-800
                    bg-gradient-to-r from-slate-50 to-slate-100 
                    dark:from-slate-900 dark:to-slate-800 p-4">
            <label class="text-xs font-semibold uppercase tracking-wider
                          text-slate-500 dark:text-slate-400 block mb-2">
                Periode PKL
            </label>
            <div class="flex items-center gap-3 text-slate-700 dark:text-slate-300">
                <div class="flex items-center gap-2">
                    <span class="font-semibold">
                        {{ \Carbon\Carbon::parse($selectedSubmission->start_date)->translatedFormat('d F Y') }}
                    </span>
                </div>
                <span class="text-slate-400">—</span>
                <div class="flex items-center gap-2">
                    <span class="font-semibold">
                        {{ \Carbon\Carbon::parse($selectedSubmission->finish_date)->translatedFormat('d F Y') }}
                    </span>
                </div>
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                Durasi: {{ \Carbon\Carbon::parse($selectedSubmission->start_date)->diffInDays($selectedSubmission->finish_date) }} hari
            </p>
        </div>

        @if ($selectedSubmission->isApproved() && $selectedSubmission->approved_at)
            <div class="bg-green-50 dark:bg-green-950/30 p-4 rounded-lg border border-green-200 dark:border-green-800">
                <div class="flex items-center gap-3">
                    <div>
                        <p class="font-semibold text-green-900 dark:text-green-300">Pengajuan Diterima</p>
                        <p class="text-sm text-green-700 dark:text-green-400">
                            {{ $selectedSubmission->approved_at->translatedFormat('d F Y, H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        @elseif ($selectedSubmission->isRejected())
            <div class="bg-red-50 dark:bg-red-950/30 p-4 rounded-lg border border-red-200 dark:border-red-800">
                <div class="flex items-center gap-3">
                    <div>
                        <p class="font-semibold text-red-900 dark:text-red-300">Pengajuan Ditolak</p>
                        <p class="text-sm text-red-700 dark:text-red-400">
                            Silakan ajukan kembali dengan data yang tepat
                        </p>
                    </div>
                </div>
            </div>
        @elseif ($selectedSubmission->isCancelled())
            <div class="bg-slate-50 dark:bg-slate-900 p-4 rounded-lg border border-slate-200 dark:border-slate-800">
                <div class="flex items-center gap-3">
                    <div>
                        <p class="font-semibold text-slate-900 dark:text-slate-300">Pengajuan Dibatalkan</p>
                        <p class="text-sm text-slate-700 dark:text-slate-400">
                            Pengajuan lain telah diterima
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div>
            <label class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 block mb-3">
                Dokumen Persyaratan
            </label>
            <div class="grid grid-cols-1 gap-3">
                @forelse ($selectedSubmission->certificates as $certificate)
                    <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                            </div>
                            <div>
                                <p class="font-medium text-slate-700 dark:text-slate-300">
                                    @switch($certificate->type)
                                        @case('industrial_visit')
                                            Kunjungan Industri
                                            @break
                                        @case('competency_test')
                                            Uji Kompetensi
                                            @break
                                        @case('spp_card')
                                            Kartu SPP
                                            @break
                                        @default
                                            {{ $certificate->type }}
                                    @endswitch
                                </p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                    {{ basename($certificate->file_path) }}
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
                    <p class="text-sm text-slate-500 dark:text-slate-400 text-center py-4">
                        Tidak ada dokumen tersedia
                    </p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-800 flex justify-end gap-3">
        <button
            wire:click="closeDetail"
            class="btn btn-ghost">
            Tutup
        </button>
    </div>
</div>