<x-slot:title>
    Daftar Siswa PKL
</x-slot:title>

<div class="flex flex-col gap-4">

    <x-ui.breadcrumbs :items="[
        'Surat PKL' => [
            'url' => route('teacher.submission-letter'),
            'icon' => 'document-text'
        ],
        'Daftar Siswa' => [
            'url' => '#',
            'icon' => 'users'
        ],
    ]" />

    <x-ui.pageheader
        title="Daftar Siswa PKL"
        :subtitle="$companyLabel ? 'Perusahaan: ' . $companyLabel : 'Detail per perusahaan'" />

    {{-- ===================== TABEL SISWA ===================== --}}
    <div class="overflow-x-auto">
        <x-ui.table :columns="['No', 'Nama Siswa', 'Status Surat', 'Diajukan', 'Aksi']">

            @forelse ($letters as $index => $letter)
            @php
                $submission   = $letter['submission'];
                $user         = $submission['user'];
                $letterId     = $letter['id'];
                $submissionId = $submission['id'];
                $status       = $letter['status'];
                $createdAt    = \Carbon\Carbon::parse($letter['created_at'])->translatedFormat('d F Y, H:i');
            @endphp

            <tr class="text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900 theme-transition duration-200">

                {{-- No --}}
                <td class="px-4 py-3 text-slate-400 text-sm w-10">{{ $index + 1 }}</td>

                {{-- Nama --}}
                <td class="px-4 py-3 font-medium">{{ $user['fullname'] ?? 'N/A' }}</td>

                {{-- Status --}}
                <td class="px-4 py-3">
                    @if ($status === 'requested')
                        <x-ui.badge variant="warning" size="sm">Menunggu</x-ui.badge>
                    @elseif ($status === 'approved')
                        <x-ui.badge variant="success" size="sm">Diterima</x-ui.badge>
                    @elseif ($status === 'rejected')
                        <x-ui.badge variant="danger" size="sm">Ditolak</x-ui.badge>
                    @endif
                </td>

                {{-- Diajukan --}}
                <td class="px-4 py-3 text-xs text-slate-400">{{ $createdAt }}</td>

                {{-- Aksi --}}
                <td class="px-4 py-3">
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
                <td colspan="5" class="text-center py-8">
                    <div class="flex flex-col items-center gap-3 text-slate-500 dark:text-slate-400">
                        <span class="text-sm font-medium">Belum ada siswa yang mengajukan surat.</span>
                    </div>
                </td>
            </tr>
            @endforelse

        </x-ui.table>
    </div>

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
