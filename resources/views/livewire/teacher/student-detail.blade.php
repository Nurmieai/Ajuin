{{-- resources/views/livewire/teacher/student-detail.blade.php --}}
<template x-teleport="body">
    <dialog id="student_detail_modal" class="modal backdrop-blur-sm" wire:ignore.self>
        <div class="modal-box w-full max-w-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 shadow-xl rounded-xl p-0 overflow-hidden flex flex-col max-h-[90vh]">

            @if($selectedStudent)
            {{-- Header --}}
            <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-900/50 shrink-0">
                <div>
                    <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100">Detail Profil Siswa</h3>
                    <div class="flex gap-2 mt-1">
                        @if($selectedStudent->hasApprovedSubmission())
                        <x-ui.badge variant="info" size="xs">PKL Diterima</x-ui.badge>
                        @endif
                    </div>
                </div>
                <button wire:click="closeDetail" class="btn btn-sm btn-circle btn-ghost">✕</button>
            </div>

            {{-- Body (Scrollable) --}}
            <div class="p-8 space-y-6 overflow-y-auto">
                {{-- Identitas --}}
                <div class="bg-blue-50 dark:bg-blue-950/30 p-5 rounded-lg border border-blue-200 dark:border-blue-800">
                    <h4 class="font-semibold text-blue-900 dark:text-blue-300 mb-4 flex items-center gap-2">
                        <x-ui.icon name="user" size="sm" />
                        Identitas Siswa
                    </h4>
                    <div class="grid grid-cols-2 gap-y-4 gap-x-6">
                        <div>
                            <label class="text-[10px] font-bold uppercase tracking-widest text-blue-700 dark:text-blue-500 block">Nama Lengkap</label>
                            <p class="mt-0.5 font-semibold text-blue-900 dark:text-blue-300">{{ $selectedStudent->fullname }}</p>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold uppercase tracking-widest text-blue-700 dark:text-blue-500 block">NISN</label>
                            <p class="mt-0.5 text-blue-900 dark:text-blue-300">{{ $selectedStudent->nisn }}</p>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold uppercase tracking-widest text-blue-700 dark:text-blue-500 block">Email</label>
                            <p class="mt-0.5 text-blue-900 dark:text-blue-300 break-all">{{ $selectedStudent->email }}</p>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold uppercase tracking-widest text-blue-700 dark:text-blue-500 block">Jurusan</label>
                            <p class="mt-0.5 text-blue-900 dark:text-blue-300">{{ $selectedStudent->major?->name ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Status Statistik --}}
                <div class="grid grid-cols-3 gap-4">
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                        <span class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Total Pengajuan</span>
                        <p class="text-2xl font-black text-slate-800 dark:text-slate-200">{{ $selectedStudent->submissions->count() }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                        <span class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Diterima</span>
                        <p class="text-2xl font-black text-green-600 dark:text-green-500">{{ $selectedStudent->submissions->where('status', 'approved')->count() }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                        <span class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Status Akun</span>
                        <div class="mt-1">
                            @if($selectedStudent->trashed())
                            <x-ui.badge variant="danger" size="xs">Arsip</x-ui.badge>
                            @elseif($selectedStudent->is_active)
                            <x-ui.badge variant="success" size="xs">Aktif</x-ui.badge>
                            @else
                            <x-ui.badge variant="warning" size="xs">Nonaktif</x-ui.badge>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Histori Pengajuan --}}
                <div>
                    <h4 class="font-semibold text-slate-700 dark:text-slate-300 mb-4 flex items-center gap-2">
                        <x-ui.icon name="clock" size="sm" />
                        Histori Pengajuan PKL
                    </h4>
                    <div class="space-y-3">
                        @forelse($selectedStudent->submissions as $sub)
                        <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 transition-all hover:bg-white dark:hover:bg-slate-900">
                            <div class="flex justify-between items-start gap-4">
                                <div class="space-y-1">
                                    <p class="font-bold text-slate-800 dark:text-slate-200 leading-tight">{{ $sub->company_name }}</p>
                                    <p class="text-xs text-slate-500 flex items-center gap-1">
                                        <x-ui.icon name="calendar" size="xs" />
                                        {{ $sub->start_date->format('d/m/Y') }} - {{ $sub->finish_date->format('d/m/Y') }}
                                    </p>
                                    <p class="text-[11px] text-slate-400">
                                        {{ $sub->certificates->count() }} Dokumen Persyaratan
                                    </p>
                                </div>
                                <x-ui.badge :variant="$sub->status === 'approved' ? 'success' : ($sub->status === 'rejected' ? 'danger' : 'warning')" size="xs" class="capitalize">
                                    {{ $sub->status }}
                                </x-ui.badge>
                            </div>
                        </div>
                        @empty
                        <div class="flex flex-col items-center justify-center py-10 px-4 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-xl">
                            <p class="text-sm text-slate-400 italic">Belum ada histori pengajuan PKL</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="p-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-800 text-right shrink-0">
                <button
                    type="button"
                    onclick="document.getElementById('student_detail_modal').close()"
                    wire:click="closeDetail"
                    class="btn px-8 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-200 border-none hover:bg-slate-300 dark:hover:bg-slate-700 transition-colors">
                    Tutup
                </button>
            </div>
            @else
            <div class="p-20 flex flex-col items-center justify-center gap-4">
                <span class="loading loading-spinner loading-lg text-blue-600"></span>
                <p class="text-sm text-slate-500 animate-pulse">Memuat data siswa...</p>
            </div>
            @endif
        </div>
        <form method="dialog" class="modal-backdrop"><button wire:click="closeDetail">close</button></form>
    </dialog>
</template>

@script
<script>
    $wire.on('open-student-detail-modal', () => {
        const modal = document.getElementById('student_detail_modal');
        if (modal) modal.showModal();
    });
    $wire.on('close-student-detail-modal', () => {
        const modal = document.getElementById('student_detail_modal');
        if (modal) modal.close();
    });
</script>
@endscript