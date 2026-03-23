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
            <tr class="hover:bg-slate-50 dark:hover:bg-slate-900 theme-transition text-slate-700 dark:text-slate-300">
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
    {{-- Karena detail.blade.php sudah kita bungkus dengan x-ui.modal, kita cukup meng-include-nya saja tanpa div pembungkus tambahan --}}
    @include('livewire.teacher.submission.detail')

    {{-- Modal Konfirmasi Batal (Danger Type dengan Alert Custom via Slot) --}}
    <x-ui.confirmation
        :open="$confirmingAction === 'cancel'"
        type="danger"
        title="Batalkan Pengajuan"
        :message="'Yakin ingin membatalkan pengajuan dari ' . ($selectedSubmission?->user->fullname ?? '') . '?'"
        confirmText="Ya, Batalkan"
        cancelText="Batal"
        confirmAction="cancel"
        wire:key="confirm-cancel">

        <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg text-xs text-yellow-800 dark:text-yellow-300 flex gap-2 items-start mt-2">
            <x-ui.icon name="exclamation-triangle" class="size-4 shrink-0 mt-0.5" />
            <span>Tindakan ini akan mengubah status pengajuan kembali menjadi dibatalkan.</span>
        </div>
    </x-ui.confirmation>

</div>