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

    <div>
        <div class="flex justify-between items-end w-full ">
            {{-- Search Bar --}}
            <x-ui.search wire:model.live.debounce.300ms="search" class="mb-4" />

            {{-- Tabs --}}
            <div role="tablist" class="tabs tabs-bordered flex justify-end -mb-[1px] relative z-10 theme-transition">
                <button
                    wire:click="setTab('approved')"
                    role="tab"
                    class="tab h-auto py-2 {{ $activeTab === 'approved' ? 'tab-active bg-white dark:bg-slate-900 border-x border-t border-slate-200 dark:border-slate-800 rounded-t-lg theme-transition' : 'border-b-transparent' }}">
                    Diterima
                </button>
                <button
                    wire:click="setTab('rejected')"
                    role="tab"
                    class="tab h-auto py-2 {{ $activeTab === 'rejected' ? 'tab-active bg-white dark:bg-slate-900 border-x border-t border-slate-200 dark:border-slate-800 rounded-t-lg theme-transition' : 'border-b-transparent' }}">
                    Ditolak
                </button>
                <button
                    wire:click="setTab('cancelled')"
                    role="tab"
                    class="tab h-auto py-2 {{ $activeTab === 'cancelled' ? 'tab-active bg-white dark:bg-slate-900 border-x border-t border-slate-200 dark:border-slate-800 rounded-t-lg theme-transition' : 'border-b-transparent' }}">
                    Dibatalkan
                </button>
            </div>
        </div>

        {{-- Table --}}
        <x-ui.table
            :flatRight="$activeTab === 'cancelled'"
            :columns="['Nama Siswa', 'Nama Perusahaan', 'Tanggal Mulai', 'Tanggal Selesai', 'Aksi']">
            @forelse ($submissions as $submission)
            <tr class="hover:bg-slate-50 dark:hover:bg-slate-900 theme-transition">
                <td class="font-medium">{{ $submission->user->fullname }}</td>
                <td>{{ $submission->company_name }}</td>
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
                <td colspan="5" class="text-center py-12">
                    <div class="flex flex-col items-center gap-3 text-slate-500 dark:text-slate-400">
                        <p class="font-medium">
                            @if($activeTab === 'approved')
                            Belum ada pengajuan yang diterima
                            @elseif($activeTab === 'rejected')
                            Belum ada pengajuan yang ditolak
                            @else
                            Belum ada pengajuan yang dibatalkan
                            @endif
                        </p>
                    </div>
                </td>
            </tr>
            @endforelse
        </x-ui.table>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $submissions->links() }}
        </div>
    </div>

    {{-- Modal Detail --}}
    @if ($showDetailModal && $selectedSubmission)
    <div class="fixed inset-0 bg-black/50 dark:bg-black/70 flex items-center justify-center z-50"
        wire:click.self="closeDetail">
        @include('livewire.teacher.submission.detail')
    </div>
    @endif

    {{-- Modal Cancel --}}
    <dialog id="cancelModal" class="modal" wire:ignore.self>
        <div class="modal-box">
            <h3 class="font-bold text-lg text-error">Konfirmasi Batalkan Pengajuan</h3>
            <p class="py-4">
                Yakin ingin membatalkan pengajuan dari
                <span class="font-semibold text-error">
                    {{ $selectedSubmission?->user->fullname ?? '' }}
                </span>?
            </p>
            <div class="alert alert-warning text-sm">
                <span>Tindakan ini akan mengubah status pengajuan kembali menjadi dibatalkan.</span>
            </div>

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