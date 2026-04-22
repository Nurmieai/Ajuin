<x-slot:title>
    Pengelolaan Surat PKL
</x-slot:title>

<div class="flex flex-col gap-4">

    <x-ui.breadcrumbs :items="[
        'Surat PKL' => [
            'url' => route('teacher.submission-letter'),
            'icon' => 'document-text'
        ],
    ]" />

    <x-ui.pageheader
        title="Pengelolaan Surat PKL"
        subtitle="Kelola surat pengajuan PKL siswa per perusahaan." />

    <x-ui.search wire:model.live.debounce.300ms="search" />

    {{-- ===================== TABEL PERUSAHAAN ===================== --}}
    <div class="overflow-x-auto">
        <x-ui.table :columns="['Perusahaan', 'Siswa', 'Periode', 'Status Surat', 'Aksi']">

            @forelse ($grouped as $groupKey => $letters)
            @php
                $firstSubmission = $letters->first()->submission;
                $isMitra         = !is_null($firstSubmission->partner_id);

                $allApproved  = $letters->every(fn($l) => $l->status === 'approved');
                $anyRequested = $letters->contains(fn($l) => $l->status === 'requested');
                $anyRejected  = $letters->contains(fn($l) => $l->status === 'rejected');

                $startDates  = $letters->map(fn($l) => $l->submission->start_date)->sort();
                $finishDates = $letters->map(fn($l) => $l->submission->finish_date)->sort();
                $periodStart = $startDates->first()?->format('d/m/Y');
                $periodEnd   = $finishDates->last()?->format('d/m/Y');
                $count       = $letters->count();
            @endphp

            <tr class="text-slate-700 dark:text-slate-300 theme-transition duration-200 hover:bg-slate-50 dark:hover:bg-slate-900">

                {{-- Perusahaan --}}
                <td class="px-4 py-3 font-medium">
                    {{ $firstSubmission->company_name }}
                    @if($isMitra)
                        <x-ui.badge variant="info" size="xs" class="ml-1">Mitra</x-ui.badge>
                    @endif
                </td>

                {{-- Jumlah Siswa --}}
                <td class="px-4 py-3">
                    <span class="text-sm">{{ $count }} siswa</span>
                </td>

                {{-- Periode --}}
                <td class="px-4 py-3 text-sm whitespace-nowrap">
                    {{ $periodStart }} — {{ $periodEnd }}
                </td>

                {{-- Status Keseluruhan --}}
                <td class="px-4 py-3">
                    @if ($allApproved)
                        <x-ui.badge variant="success" size="sm" class="whitespace-nowrap">Semua Diterima</x-ui.badge>
                    @elseif ($anyRequested)
                        <x-ui.badge variant="warning" size="sm" class="whitespace-nowrap">Ada Menunggu</x-ui.badge>
                    @elseif ($anyRejected)
                        <x-ui.badge variant="danger" size="sm" class="whitespace-nowrap">Ada Ditolak</x-ui.badge>
                    @else
                        <x-ui.badge variant="neutral" size="sm">-</x-ui.badge>
                    @endif
                </td>

                {{-- Aksi --}}
                <td class="px-4 py-3">
                    <button
                        wire:click="openStudentModal('{{ $groupKey }}')"
                        class="btn btn-sm btn-outline btn-info gap-1">
                        <x-ui.icon name="users" size="sm" />
                        Lihat Siswa
                    </button>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center py-8">
                    <div class="flex flex-col items-center gap-3 text-slate-500 dark:text-slate-400">
                        <span class="text-sm font-medium">Belum ada pengajuan surat PKL</span>
                    </div>
                </td>
            </tr>
            @endforelse

        </x-ui.table>
    </div>

    <div class="mt-4 flex justify-center">
        {{ $paginator->links('components.ui.pagination') }}
    </div>

    {{-- ===================== MODAL DAFTAR SISWA ===================== --}}
    <dialog id="studentModal" class="modal">
        <div class="modal-box max-w-4xl">

            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-bold text-lg">Daftar Siswa PKL</h3>
                    @if($selectedCompanyLabel)
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $selectedCompanyLabel }}</p>
                    @endif
                </div>
                <button wire:click="closeStudentModal" class="btn btn-sm btn-ghost btn-circle">✕</button>
            </div>

            <div class="overflow-x-auto">
                <table class="table table-sm w-full">
                    <thead>
                        <tr class="text-slate-500 dark:text-slate-400 text-xs uppercase">
                            <th class="w-10">No</th>
                            <th>Nama Siswa</th>
                            <th>Status Surat</th>
                            <th>Diajukan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($selectedCompanyLetters as $index => $letter)
                        @php
                            $submission = $letter['submission'];
                            $user       = $submission['user'];
                            $letterId   = $letter['id'];
                            $submissionId = $submission['id'];
                            $status     = $letter['status'];
                            $createdAt  = \Carbon\Carbon::parse($letter['created_at'])->translatedFormat('d F Y, H:i');
                        @endphp
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800">

                            {{-- No --}}
                            <td class="text-slate-400 text-sm">{{ $index + 1 }}</td>

                            {{-- Nama --}}
                            <td class="font-medium">{{ $user['fullname'] ?? 'N/A' }}</td>

                            {{-- Status --}}
                            <td>
                                @if ($status === 'requested')
                                    <x-ui.badge variant="warning" size="sm">Menunggu</x-ui.badge>
                                @elseif ($status === 'approved')
                                    <x-ui.badge variant="success" size="sm">Diterima</x-ui.badge>
                                @elseif ($status === 'rejected')
                                    <x-ui.badge variant="danger" size="sm">Ditolak</x-ui.badge>
                                @endif
                            </td>

                            {{-- Diajukan --}}
                            <td class="text-xs text-slate-400">{{ $createdAt }}</td>

                            {{-- Aksi --}}
                            <td>
                                <x-ui.actions :actions="[
                                    [
                                        'label' => 'Detail',
                                        'icon'  => 'info',
                                        'color' => 'blue',
                                        'url'   => route('teacher.submission-letter-detail', $submissionId),
                                    ],
                                    [
                                        'label'  => 'Download',
                                        'icon'   => 'arrow-down-tray',
                                        'color'  => $status === 'approved' ? 'indigo' : 'gray',
                                        'url'    => $status === 'approved'
                                            ? route('teacher.submission-letter-download', $submissionId)
                                            : null,
                                        'event'  => $status !== 'approved'
                                            ? 'downloadLetter(' . $submissionId . ')'
                                            : null,
                                        'target' => '_blank',
                                        'title'  => $status !== 'approved' ? 'Surat belum diterima' : 'Unduh Surat PKL',
                                    ],
                                    $status !== 'approved' ? [
                                        'label' => 'Terima',
                                        'icon'  => 'check',
                                        'color' => 'green',
                                        'event' => 'confirmApprove(' . $letterId . ')',
                                        'title' => 'Terima surat',
                                    ] : [
                                        'label' => 'Sudah Diterima',
                                        'icon'  => 'check',
                                        'color' => 'gray',
                                        'title' => 'Surat sudah diterima',
                                    ],
                                    $status !== 'approved' ? [
                                        'label' => 'Tolak & Hapus',
                                        'icon'  => 'x',
                                        'color' => 'red',
                                        'event' => 'confirmReject(' . $letterId . ')',
                                        'title' => 'Tolak dan hapus surat, siswa wajib ajukan ulang',
                                    ] : [
                                        'label' => 'Tidak bisa ditolak',
                                        'icon'  => 'x',
                                        'color' => 'gray',
                                        'title' => 'Surat sudah diterima, tidak bisa ditolak',
                                    ],
                                ]" />
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-slate-400 text-sm">
                                Belum ada siswa yang mengajukan surat.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="modal-action">
                <button wire:click="closeStudentModal" class="btn btn-ghost btn-sm">Tutup</button>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button wire:click="closeStudentModal">close</button>
        </form>
    </dialog>

    {{-- ===================== MODAL KONFIRMASI TERIMA ===================== --}}
    <x-ui.confirmation
        :open="$isApproveOpen"
        title="Konfirmasi Terima Surat"
        confirmText="Ya, Terima"
        confirmAction="approve"
        type="success">
        <x-slot:message>
            Yakin ingin menerima surat PKL dari
            <span class="font-semibold text-primary">{{ $selectedSubmission?->user->fullname ?? '' }}</span>?
        </x-slot:message>
    </x-ui.confirmation>

    {{-- ===================== MODAL KONFIRMASI TOLAK ===================== --}}
    <x-ui.confirmation
        :open="$isRejectOpen"
        title="Konfirmasi Tolak Surat"
        confirmText="Ya, Tolak & Hapus"
        confirmAction="reject"
        type="danger">
        <x-slot:message>
            Yakin ingin menolak surat PKL dari
            <span class="font-semibold text-error">{{ $selectedSubmission?->user->fullname ?? '' }}</span>?
            <br>
            <span class="text-sm text-slate-500">Surat akan dihapus dan siswa wajib mengajukan ulang.</span>
        </x-slot:message>
    </x-ui.confirmation>

</div>

@script
<script>
    $wire.on('open-approve-modal', () => approveModal.showModal());
    $wire.on('close-approve-modal', () => approveModal.close());
    $wire.on('open-reject-modal',  () => rejectModal.showModal());
    $wire.on('close-reject-modal', () => rejectModal.close());
    $wire.on('open-student-modal', () => studentModal.showModal());
    $wire.on('close-student-modal', () => studentModal.close());
</script>
@endscript
