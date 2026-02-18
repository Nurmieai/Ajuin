<x-slot:title>
    Pengajuan PKL
</x-slot:title>

<div class="space-y-4">
    @if (session()->has('success'))
        <div class="alert alert-success shadow-sm text-sm" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-error shadow-sm text-sm" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="overflow-x-auto">
        <x-ui.table :columns="['Nama Siswa', 'Nama Perusahaan', 'Tanggal Mulai', 'Tanggal Selesai', 'Aksi']">
            @forelse ($submissions as $submission)
                <tr class="hover:bg-base-200 transition-colors">
                    <td class="font-medium">{{ $submission->user->fullname }}</td>
                    <td>{{ $submission->company_name }}</td>
                    <td>{{ $submission->start_date->format('d/m/Y') }}</td>
                    <td>{{ $submission->finish_date->format('d/m/Y') }}</td>
                    <td>
                        <div class="flex gap-2">
                            <button
                                wire:click="showDetail({{ $submission->id }})"
                                class="btn btn-sm btn-info btn-outline"
                                title="Lihat Detail">
                                Detail
                            </button>
                            
                            <button
                                wire:click="confirmApprove({{ $submission->id }})"
                                class="btn btn-sm btn-success btn-outline"
                                title="Terima Pengajuan">
                                Terima
                            </button>
                            
                            <button
                                wire:click="confirmReject({{ $submission->id }})"
                                class="btn btn-sm btn-error btn-outline"
                                title="Tolak Pengajuan">
                                Tolak
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-8">
                        <div class="flex flex-col items-center gap-3 text-slate-500 dark:text-slate-400">
                            <span class="text-sm font-medium">Belum ada pengajuan yang perlu ditinjau</span>
                        </div>
                    </td>
                </tr>
            @endforelse
        </x-ui.table>
    </div>

    @if ($showDetailModal && $selectedSubmission)
        <div class="fixed inset-0 bg-black/50 dark:bg-black/70 flex items-center justify-center z-50"
             wire:click.self="closeDetail">
            @include('livewire.teacher.submission.detail')
        </div>  
    @endif

    <dialog id="approveModal" class="modal" wire:ignore.self>
        <div class="modal-box">
            <h3 class="font-bold text-lg">Konfirmasi Terima Pengajuan</h3>
            <p class="py-4">
                Yakin ingin menerima pengajuan dari 
                <span class="font-semibold text-primary">
                    {{ $selectedSubmission?->user->fullname ?? '' }}
                </span>?
            </p>

            <div class="modal-action">
                <button class="btn btn-ghost" onclick="approveModal.close()">
                    Batal
                </button>

                <button 
                    class="btn btn-success"
                    wire:click="approve"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="approve">Ya, Terima</span>
                    <span wire:loading wire:target="approve" class="loading loading-spinner loading-sm"></span>
                </button>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <dialog id="rejectModal" class="modal" wire:ignore.self>
        <div class="modal-box">
            <h3 class="font-bold text-lg">Konfirmasi Tolak Pengajuan</h3>
            <p class="py-4">
                Yakin ingin menolak pengajuan dari 
                <span class="font-semibold text-error">
                    {{ $selectedSubmission?->user->fullname ?? '' }}
                </span>?
            </p>

            <div class="modal-action">
                <button class="btn btn-ghost" onclick="rejectModal.close()">
                    Batal
                </button>

                <button 
                    class="btn btn-error"
                    wire:click="reject"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="reject">Ya, Tolak</span>
                    <span wire:loading wire:target="reject" class="loading loading-spinner loading-sm"></span>
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
    $wire.on('open-approve-modal', () => {
        approveModal.showModal();
    });

    $wire.on('close-approve-modal', () => {
        approveModal.close();
    });

    $wire.on('open-reject-modal', () => {
        rejectModal.showModal();
    });

    $wire.on('close-reject-modal', () => {
        rejectModal.close();
    });
</script>
@endscript