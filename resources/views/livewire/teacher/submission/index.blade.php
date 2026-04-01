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
        subtitle="Kelola pengajuan PKL siswa yang masuk" />

    <div class="flex flex-row gap-4 justify-between items-center w-full">
        <div class="flex-1 w-full">
            <x-ui.search wire:model.live.debounce.300ms="search" />
        </div>

        <div class="tooltip tooltip-left sm:tooltip-top shrink-0" data-tip="Riwayat Pengajuan">

            <a wire:navigate href="{{ route('teacher.submission-history') }}"
                class="btn btn-md flex items-center gap-2
                  bg-yellow-600 hover:bg-yellow-700
                  dark:bg-yellow-500 dark:hover:bg-yellow-400
                  text-white border-none">

                <x-ui.icon name="archive" size="sm" class="stroke-[2px]" />

                <span class="hidden sm:inline-block font-medium">
                    Riwayat Pengajuan
                </span>

            </a>
        </div>
    </div>

    <div>
        <x-ui.table :columns="['Nama Siswa', 'Nama Perusahaan', 'Tanggal Mulai', 'Tanggal Selesai', 'Aksi']">
            @forelse ($submissions as $submission)
            @php
            $hasApprovedSubmission = in_array($submission->user_id, $approvedUserIds);
            @endphp
            <tr class="text-slate-700 dark:text-slate-300 transition-colors duration-200 hover:bg-slate-50 dark:hover:bg-slate-900 {{ $hasApprovedSubmission ? 'opacity-50' : '' }}">
                <td>
                    <div class="flex items-center gap-2">
                        <span class="font-medium">{{ $submission->user->fullname }}</span>
                        @if($hasApprovedSubmission)
                        <x-ui.badge variant="success" size="xs">Sudah Diterima</x-ui.badge>
                        @endif
                    </div>
                </td>
                <td>{{ $submission->company_name }}</td>
                <td>{{ $submission->start_date->format('d/m/Y') }}</td>
                <td>{{ $submission->finish_date->format('d/m/Y') }}</td>
                <td>
                    <x-ui.actions :actions="[
                        [
                            'label' => 'Detail',
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

    <div class="mx-auto justify-center">
        {{ $submissions->links() }}
    </div>

    {{-- Modal Detail (Menggunakan x-ui.modal) --}}

    @include('livewire.teacher.submission.detail')


    <x-ui.confirmation
        :open="$confirmingAction === 'approve'"
        type="success"
        title="Terima Pengajuan PKL"
        :message="'Yakin ingin menerima pengajuan dari ' . ($selectedSubmission?->user->fullname ?? '') . '?'"
        confirmText="Ya, Terima"
        cancelText="Batal"
        confirmAction="approve"
        wire:key="confirm-approve">

        {{-- Ini akan masuk ke dalam {{ $slot }} --}}
        <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg text-xs text-yellow-800 dark:text-yellow-300 flex gap-2 items-start">
            <x-ui.icon name="exclamation-triangle" class="size-4 shrink-0 mt-0.5" />
            <span>Pengajuan lain dari siswa ini akan dibatalkan secara otomatis.</span>
        </div>

    </x-ui.confirmation>

    {{-- Modal Konfirmasi Tolak (Tanpa Alert) --}}
    <x-ui.confirmation
        :open="$confirmingAction === 'reject'"
        type="danger"
        title="Tolak Pengajuan PKL"
        :message="'Apakah Anda yakin ingin menolak pengajuan dari ' . ($selectedSubmission?->user->fullname ?? '') . '?'"
        confirmText="Ya, Tolak"
        cancelText="Batal"
        confirmAction="reject"
        wire:key="confirm-reject" />

</div>