<x-slot:title>
    Pengajuan PKL
</x-slot:title>

<div class="flex flex-col gap-4">
    <x-ui.breadcrumbs :items="[
        'Pengajuan' => [
            'url' => route('teacher.submission-manage'),
            'icon' => 'edit'
        ],
    ]" />

    <x-ui.pageheader
        title="Pengajuan PKL"
        subtitle="Kelola pengajuan PKL siswa atau perbarui informasi mitra yang sudah ada." />

    <x-ui.search />

    <div class="overflow-x-auto">
        <x-ui.table :columns="['Nama Siswa', 'Nama Perusahaan', 'Tanggal Mulai', 'Tanggal Selesai', 'Aksi']">
            @forelse ($submissions as $submission)
            @php
            $hasApprovedSubmission = in_array($submission->user_id, $approvedUserIds);
            @endphp
            <tr class="text-slate-700 dark:text-slate-300
                       transition-colors duration-200
                       hover:bg-slate-50 dark:hover:bg-slate-900
                       {{ $hasApprovedSubmission ? 'opacity-50' : '' }}">
                <td>
                    <div class="flex items-center gap-2">
                        <span class="font-medium">{{ $submission->user->fullname }}</span>
                        @if($hasApprovedSubmission)
                        <span class="badge badge-success badge-xs">Sudah Diterima</span>
                        @endif
                    </div>
                </td>
                <td>{{ $submission->company_name }}</td>
                <td>{{ $submission->start_date->format('d/m/Y') }}</td>
                <td>{{ $submission->finish_date->format('d/m/Y') }}</td>
                <td>
                    <x-ui.actions :actions="[
                    [
                        'label' => 'Detail Modal',
                        'icon' => 'info',
                        'color' => 'blue',
                        'event' => 'showDetail(' . $submission->id . ')'
                    ],

                    [
                        
                        'label' => 'Terima',
                        'icon' => 'check',
                        'color' => 'green',
                        'event' => 'confirmApprove(' . $submission->id . ')'
                    ],
                    [
                        'label' => 'Tolak',
                        'icon' => 'x',
                        'color' => 'red',
                        'event' => 'confirmReject(' . $submission->id . ')'
                    ],
                ]" />
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

    {{-- pagination --}}
    <div class="mx-auto justify-center">
        {{ $submissions->links() }}
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
            <div class="alert alert-warning text-sm">
                <span>Pengajuan lain dari siswa ini akan dibatalkan secara otomatis</span>
            </div>

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
    <x-ui.toast />
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
