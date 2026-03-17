<x-slot:title>
    Riwayat Pengajuan
</x-slot:title>

<div class="flex flex-col gap-4">
    <x-ui.breadcrumbs :items="[
        'Pengajuan' => [
            'url' => route('teacher.submission-manage'),
            'icon' => 'edit'
        ],
        'History' => [
            'url' => route('teacher.submission-history'),
            'icon' => 'edit'
        ],
    ]" />

    <x-ui.pageheader
        title="Riwayat Pengajuan PKL"
        subtitle="Kelola pengajuan PKL siswa yang sudah dikelola" />

<div class="flex flex-row gap-4 justify-between items-center">
    <x-ui.search />
            <div role="tablist" class="tabs tabs-bordered w-full flex justify-end content-end">
                <button
                    wire:click="setTab('approved')"
                    role="tab"
                    class="tab {{ $activeTab === 'approved' ? 'tab-active dark:bg-slate-900 border-x-1 border-t-1 border-slate-200 dark:border-slate-800 rounded-t-lg

' : '' }}">
                    Diterima
                </button>
                <button
                    wire:click="setTab('rejected')"
                    role="tab"
                    class="tab {{ $activeTab === 'rejected' ? 'tab-active dark:bg-slate-900 border-x-1 border-t-1 border-slate-200 dark:border-slate-800 rounded-t-lg' : '' }}">
                    Ditolak
                </button>
                <button
                    wire:click="setTab('cancelled')"
                    role="tab"
                    class="tab {{ $activeTab === 'cancelled' ? 'tab-active dark:bg-slate-900 border-x-1 border-t-1 border-slate-200 dark:border-slate-800 rounded-t-lg' : '' }}">
                    Dibatalkan
                </button>
            </div>
</div>

    <div class="overflow-x-auto">
        <x-ui.table :columns="['Nama Siswa', 'Nama Perusahaan', 'Tanggal Mulai', 'Tanggal Selesai', 'Aksi']">
            @forelse ($submissions as $submission)
                <tr>
                <td>{{ $submission->user->fullname }}</td>
                <td>{{ $submission->company_name}}</td>
                <td>{{ $submission->start_date->format('d/m/Y') }}</td>
                <td>{{ $submission->finish_date->format('d/m/Y') }}</td>
                <td>
                    @php
                    $actions = [
                    [
                    'label' => 'Detail',
                    'icon' => 'info',
                    'color' => 'blue',
                    'event' => 'showDetail(' . $submission->id . ')'
                    ]
                    ];

                    if ($activeTab === 'approved') {
                    $actions[] = [
                    'label' => 'Batalkan',
                    'icon' => 'exclamation-circle',
                    'color' => 'red',
                    'event' => 'confirmCancel('. $submission->id .')'
                    ];
                    }
                    @endphp
                    <x-ui.actions :actions="$actions" />
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

    <dialog id="cancelModal" class="modal" wire:ignore.self>
        <div class="modal-box">
            <h3 class="font-bold text-lg">Konfirmasi Batalkan Pengajuan</h3>
            <p class="py-4">
                Yakin ingin membatalkan pengajuan dari
                <span class="font-semibold text-error">
                    {{ $selectedSubmission?->user->fullname ?? '' }}
                </span>?
            </p>

            <div class="modal-action">
                <button class="btn btn-ghost" onclick="cancelModal.close()">
                    Batal
                </button>

                <button
                    class="btn btn-error"
                    wire:click="cancel"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="cancel">Ya, Batalkan</span>
                    <span wire:loading wire:target="cancel" class="loading loading-spinner loading-sm"></span>
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
    $wire.on('open-cancel-modal', () => {
        cancelModal.showModal();
    });

    $wire.on('close-cancel-modal', () => {
        cancelModal.close();
    });
</script>
@endscript