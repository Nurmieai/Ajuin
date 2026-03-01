<div class="bg-white dark:bg-slate-950 p-8 rounded-xl shadow-xl
            w-full max-w-4xl border border-slate-200 dark:border-slate-800
            max-h-[90vh] overflow-y-auto"
     wire:click.stop>

    <div class="flex justify-between items-start mb-6 border-b border-slate-200 dark:border-slate-800 pb-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100">
                Detail Siswa
            </h2>
            <div class="flex gap-2 mt-2">
                @if($selectedStudent->isActive())
                    <span class="badge badge-success">Aktif</span>
                @elseif($selectedStudent->isInactive())
                    <span class="badge badge-warning">Nonaktif</span>
                @else
                    <span class="badge badge-error">Arsip</span>
                @endif

                @if($selectedStudent->hasApprovedSubmission())
                    <span class="badge badge-info">PKL Diterima</span>
                @endif
            </div>
        </div>
        <button 
            wire:click="closeDetail"
            class="btn btn-sm btn-circle btn-ghost">
        </button>
    </div>

    <div class="space-y-6">
        <div class="bg-blue-50 dark:bg-blue-950/30 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
            <h3 class="font-semibold text-blue-900 dark:text-blue-300 mb-3 flex items-center gap-2">
                Identitas Siswa
            </h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wider text-blue-700 dark:text-blue-400">
                        Nama Lengkap
                    </label>
                    <p class="mt-1 text-lg font-medium text-blue-900 dark:text-blue-300">
                        {{ $selectedStudent->fullname }}
                    </p>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wider text-blue-700 dark:text-blue-400">
                        NISN
                    </label>
                    <p class="mt-1 text-blue-900 dark:text-blue-300">
                        {{ $selectedStudent->nisn }}
                    </p>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wider text-blue-700 dark:text-blue-400">
                        Email
                    </label>
                    <p class="mt-1 text-blue-900 dark:text-blue-300">
                        {{ $selectedStudent->email }}
                    </p>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wider text-blue-700 dark:text-blue-400">
                        Jurusan
                    </label>
                    <p class="mt-1 text-blue-900 dark:text-blue-300">
                        {{ $selectedStudent->major?->name ?? '-' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div class="stat bg-base-200 rounded-lg">
                <div class="stat-title text-xs">Total Pengajuan</div>
                <div class="stat-value text-2xl">{{ $selectedStudent->getTotalSubmissions() }}</div>
            </div>
            <div class="stat bg-base-200 rounded-lg">
                <div class="stat-title text-xs">Diterima</div>
                <div class="stat-value text-2xl text-success">{{ $selectedStudent->getApprovedSubmissions()->count() }}</div>
            </div>
            <div class="stat bg-base-200 rounded-lg">
                <div class="stat-title text-xs">Status Akun</div>
                <div class="stat-value text-xl">
                    @if($selectedStudent->isActive())
                        <span class="text-success">Aktif</span>
                    @elseif($selectedStudent->isInactive())
                        <span class="text-warning">Nonaktif</span>
                    @else
                        <span class="text-error">Arsip</span>
                    @endif
                </div>
            </div>
        </div>

        <div>
            <h3 class="font-semibold text-slate-700 dark:text-slate-300 mb-3 flex items-center gap-2">
                Histori Pengajuan PKL
            </h3>

            @forelse($selectedStudent->submissions as $submission)
                <div class="bg-slate-50 dark:bg-slate-900 p-4 rounded-lg border border-slate-200 dark:border-slate-800 mb-3">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h4 class="font-semibold text-slate-800 dark:text-slate-200">
                                    {{ $submission->company_name }}
                                </h4>
                                <span class="badge {{ $submission->getStatusBadgeClass() }} badge-sm">
                                    {{ $submission->getStatusLabel() }}
                                </span>
                            </div>
                            <div class="text-sm text-slate-600 dark:text-slate-400 space-y-1">
                                <p>{{ $submission->company_email }}</p>
                                <p>{{ $submission->start_date->format('d/m/Y') }} - {{ $submission->finish_date->format('d/m/Y') }}</p>
                                <p>{{ $submission->certificates->count() }} Dokumen</p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-slate-500 dark:text-slate-400">
                    <p class="text-sm">Belum ada histori pengajuan PKL</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-800 flex justify-end">
        <button
            wire:click="closeDetail"
            class="btn btn-ghost">
            Tutup
        </button>
    </div>
</div>