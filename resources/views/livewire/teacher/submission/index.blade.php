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

    <div class="flex flex-col w-full">
        <div class="flex flex-col gap-4 md:hidden">
            <div class="flex items-center gap-2">
                <div class="flex-1">
                    <x-ui.search wire:model.live.debounce.300ms="search" />
                </div>
                <a wire:navigate href="{{ route('teacher.submission-history') }}"
                    class="btn btn-md bg-yellow-600 hover:bg-yellow-700 text-white border-none shrink-0">
                    <x-ui.icon name="archive" size="sm" />
                </a>
            </div>

            <div role="tablist" class="tabs tabs-bordered grid grid-cols-2 -mb-[1px] relative z-10 theme-transition">
                <button
                    wire:click="setTab('mandiri')"
                    role="tab"
                    title="Mandiri"
                    class="tab flex-none h-auto py-2 px-6 self-start
                    {{ $activeTab === 'mandiri' 
                        ? 'tab-active bg-slate-50 dark:bg-slate-900 border-x border-t border-slate-200 dark:border-slate-800 rounded-t-lg theme-transition' 
                        : 'border-b-transparent' }}">
                    <div class="flex items-center justify-center gap-2">
                        <x-ui.icon name="users" size="sm" class="text-slate-700 dark:text-slate-300" />
                        <span class="font-medium text-sm text-slate-700 dark:text-slate-300">Mandiri</span>
                    </div>
                </button>
                <button
                    wire:click="setTab('mitra')"
                    role="tab"
                    title="Mitra"
                    class="tab flex-none h-auto py-2 px-6 self-start
                    {{ $activeTab === 'mitra' 
                        ? 'tab-active bg-slate-50 dark:bg-slate-900 border-x border-t border-slate-200 dark:border-slate-800 rounded-t-lg' 
                        : 'border-b-transparent' }}">
                    <div class="flex items-center justify-center gap-2">
                        <x-ui.icon name="user-plus" size="sm" class="text-slate-700 dark:text-slate-300" />
                        <span class="font-medium text-sm text-slate-700 dark:text-slate-300">Mitra</span>
                    </div>
                </button>
            </div>
        </div>

        <div class="hidden md:flex justify-end items-end">
            <div class="flex-1 mb-4 me-4">
                <x-ui.search wire:model.live.debounce.300ms="search" />
            </div>
            <div role="tablist" class="tabs tabs-bordered flex justify-center md:justify-end -mb-[1px] relative z-10 w-full md:w-auto theme-transition">
                <button
                    wire:click="setTab('mandiri')"
                    role="tab"
                    title="Mandiri"
                    class="tab flex-none h-auto py-2 px-6 self-start mt-[16px]
                    {{ $activeTab === 'mandiri' 
                        ? 'tab-active bg-slate-50 dark:bg-slate-900 border-x border-t border-slate-200 dark:border-slate-800 rounded-t-lg theme-transition' 
                        : 'border-b-transparent' }}">
                    <div class="flex items-center justify-center gap-2">
                        <x-ui.icon name="users" size="sm" class="text-slate-700 dark:text-slate-300" />
                        <span class="font-medium text-sm text-slate-700 dark:text-slate-300">Mandiri</span>
                    </div>
                </button>

                <button
                    wire:click="setTab('mitra')"
                    role="tab"
                    title="Mitra"
                    class="tab flex-none h-auto py-2 px-6 self-start mt-[16px]
                    {{ $activeTab === 'mitra' 
                        ? 'tab-active bg-slate-50 dark:bg-slate-900 border-x border-t border-slate-200 dark:border-slate-800 rounded-t-lg' 
                        : 'border-b-transparent' }}">
                    <div class="flex items-center justify-center gap-2">
                        <x-ui.icon name="user-plus" size="sm" class="text-slate-700 dark:text-slate-300" />
                        <span class="font-medium text-sm text-slate-700 dark:text-slate-300">Mitra</span>
                    </div>
                </button>
                <div class="ms-4">
                    <div class="tooltip tooltip-left sm:tooltip-top shrink-0" data-tip="Riwayat Pengajuan">
                        <a wire:navigate href="{{ route('teacher.submission-history') }}"
                            class="btn btn-md flex items-center gap-2
                    bg-yellow-600 hover:bg-yellow-700
                    dark:bg-yellow-500 dark:hover:bg-yellow-400
                    text-white border-none">
                            <x-ui.icon name="archive" size="sm" class="stroke-[2px]" />
                            <span class="font-medium">Riwayat Pengajuan</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Row 2: Tabel --}}
        <x-ui.table
            :flatRight="$activeTab === 'mitra'"
            :flatLeft="$activeTab === 'mandiri'"
            :columns="['Nama Siswa', 'Nama Perusahaan', 'Tanggal Mulai', 'Tanggal Selesai', 'Aksi']">

            @forelse ($submissions as $submission)
            <tr class="hover:bg-slate-50 dark:hover:bg-slate-900 theme-transition text-slate-700 dark:text-slate-300">
                <td>
                    <div class="flex items-center gap-2">
                        <span class="font-medium">{{ $submission->user->fullname }}</span>
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
                <td colspan="5" class="text-center py-12">
                    <div class="flex flex-col items-center gap-3 text-slate-500 dark:text-slate-400">
                        <p class="font-medium">Belum ada pengajuan yang perlu ditinjau</p>
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

    {{-- Include Detail Modal --}}
    @include('livewire.teacher.submission.detail')

    {{-- Confirmation Modals --}}
    <x-ui.confirmation
        :open="$confirmingAction === 'approve'"
        type="success"
        title="Terima Pengajuan PKL"
        :message="'Yakin ingin menerima pengajuan dari ' . ($selectedSubmission?->user->fullname ?? '') . '?'"
        confirmText="Ya, Terima"
        cancelText="Batal"
        confirmAction="approve"
        wire:key="confirm-approve">

        <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg text-xs text-yellow-800 dark:text-yellow-300 flex gap-2 items-start mt-2">
            <x-ui.icon name="exclamation-triangle" class="size-4 shrink-0 mt-0.5" />
            <span>Pengajuan lain dari siswa ini akan dibatalkan secara otomatis.</span>
        </div>
    </x-ui.confirmation>

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