<x-slot:title>
    Kelola Pengajuan PKL
</x-slot:title>

<div class="space-y-4">
    @if (session()->has('success'))
        <div class="alert alert-success shadow-sm text-sm" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-error shadow-sm text-sm" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if ($hasApprovedSubmission)
        <div class="alert alert-info shadow-sm">
            <div>
                <h3 class="font-bold">Pengajuan Diterima</h3>
                <div class="text-xs">Pengajuan lain telah dibatalkan secara otomatis</div>
            </div>
        </div>
    @endif

    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Kelola Pengajuan</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kelola semua pengajuan PKL Anda</p>
        </div>
        <a href="{{ route('student.submission-create') }}" 
           class="btn btn-primary btn-sm"
           @if($hasApprovedSubmission) disabled @endif>
            Buat Pengajuan Baru
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="table table-zebra">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Perusahaan</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($submissions as $index => $submission)
                    <tr class="hover:bg-base-200 transition-colors">
                        <td>{{ $index + 1 }}</td>
                        <td class="font-medium">{{ $submission->company_name }}</td>
                        <td>{{ $submission->start_date->format('d/m/Y') }}</td>
                        <td>{{ $submission->finish_date->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge {{ $submission->getStatusBadgeClass() }} badge-sm">
                                {{ $submission->getStatusLabel() }}
                            </span>
                        </td>
                        <td>
                            <div class="flex justify-center gap-2">
                                <button
                                    wire:click="showDetail({{ $submission->id }})"
                                    class="btn btn-sm btn-info btn-outline"
                                    title="Lihat Detail">
                                    Detail
                                </button>

                                @if ($submission->canBeEdited())
                                    <button
                                        wire:click="redirectToEdit({{ $submission->id }})"
                                        class="btn btn-sm btn-warning btn-outline"
                                        title="Edit Pengajuan">
                                        Edit
                                    </button>
                                @else
                                    <button
                                        class="btn btn-sm btn-disabled"
                                        disabled
                                        title="Tidak dapat diubah">
                                        Edit
                                    </button>
                                @endif

                                @if ($submission->canBeDeleted())
                                    <button
                                        wire:click="confirmDelete({{ $submission->id }})"
                                        class="btn btn-sm btn-error btn-outline"
                                        title="Hapus Pengajuan">
                                        Hapus
                                    </button>
                                @else
                                    <button
                                        class="btn btn-sm btn-disabled"
                                        disabled
                                        title="Tidak dapat dihapus">
                                        Hapus
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-12">
                            <div class="flex flex-col items-center gap-3 text-slate-500 dark:text-slate-400">
                                <div class="text-center">
                                    <p class="font-medium">Belum ada pengajuan</p>
                                    <p class="text-sm mt-1">Klik tombol "Buat Pengajuan Baru" untuk memulai</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>


    @if ($showDetailModal && $selectedSubmission)
        <div class="fixed inset-0 bg-black/50 dark:bg-black/70 flex items-center justify-center z-50"
             wire:click.self="closeDetail">
            @include('livewire.student.academic-service.submission.detail')
        </div>
    @endif

    <dialog id="deleteModal" class="modal" wire:ignore.self>
        <div class="modal-box">
            <h3 class="font-bold text-lg text-error">Konfirmasi Hapus Pengajuan</h3>
            <p class="py-4">
                Yakin ingin menghapus pengajuan ke 
                <span class="font-semibold text-error">{{ $selectedSubmission?->company_name ?? '' }}</span>?
            </p>
            <p class="text-sm text-slate-500">
                Tindakan ini tidak dapat dibatalkan. Semua dokumen yang terkait akan dihapus.
            </p>

            <div class="modal-action">
                <button class="btn btn-ghost" onclick="deleteModal.close()">
                    Batal
                </button>

                <button 
                    class="btn btn-error"
                    wire:click="delete"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="delete">Ya, Hapus</span>
                    <span wire:loading wire:target="delete" class="loading loading-spinner loading-sm"></span>
                </button>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
</div>

@script
<script>
    $wire.on('open-delete-modal', () => {
        deleteModal.showModal();
    });

    $wire.on('close-delete-modal', () => {
        deleteModal.close();
    });
</script>
@endscript