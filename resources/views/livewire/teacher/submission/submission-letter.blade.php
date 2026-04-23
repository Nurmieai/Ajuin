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
                    <a
                        wire:navigate
                        href="{{ route('teacher.submission-letter-group', ['groupKey' => $groupKey]) }}"
                        class="btn btn-sm btn-outline btn-info gap-1">
                        <x-ui.icon name="users" size="sm" />
                        Lihat Siswa
                    </a>
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

</div>
