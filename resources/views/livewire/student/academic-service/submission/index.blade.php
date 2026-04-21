<x-slot:title>
    Kelola Pengajuan PKL
</x-slot:title>

<div class="flex flex-col gap-4">

    <x-ui.breadcrumbs :items="[
        'Layanan Akademik' => [
            'url' => route('student.academic-service'),
            'icon' => 'academic-cap'
        ],
        'Kelola Pengajuan' => [
            'url' => route('student.submission-manage'),
            'icon' => 'academic-cap'
        ],
    ]" />

    <x-ui.pageheader
        title="Kelola Pengajuan PKL"
        subtitle="Kelola semua pengajuan PKL Anda"
        :subtitle="[
            'teacher' => 'Daftar pengajuan PKL siswa yang sudah diterima.',
            'student' => 'Daftar pengajuan PKL yang sudah diterima.']" />

    @if ($hasApprovedSubmission)
    <div class="alert alert-info shadow-sm dark:bg-blue-500 text-white border-none">
        <div>
            <h3 class="font-bold">Pengajuan Diterima</h3>
            <div class="text-xs">Pengajuan lain telah dibatalkan secara otomatis</div>
        </div>
    </div>
    @endif

    <div class="flex justify-between items-center gap-4">
        <x-ui.search wire:model.live.debounce.300ms="search" />

        @if($hasApprovedSubmission)
        <button
            type="button"
            x-on:click="$dispatch('error', ['Anda sudah memiliki pengajuan yang diterima. Tidak dapat membuat pengajuan baru.'])"
            class="btn text-xs tooltip bg-slate-300 dark:bg-slate-700 text-slate-500 cursor-not-allowed opacity-70"
            data-tip="Pengajuan sudah diterima">
            <x-ui.icon name="plus" size="sm" />
            <span class="hidden md:inline">Buat Pengajuan Baru</span>
        </button>
        @else
        <a wire:navigate href="{{ route('student.submission-create') }}"
            class="btn text-xs tooltip bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-400 text-white"
            data-tip="Buat pengajuan PKL baru">
            <x-ui.icon name="plus" size="sm" />
            <span class="hidden md:inline">Buat Pengajuan Baru</span>
        </a>
        @endif
    </div>

    <div class="overflow-x-auto">
        <x-ui.table :columns="['No', 'Nama Perusahaan', 'Periode', 'Tipe', 'Status', 'Aksi']">

            @forelse ($submissions as $index => $submission)
            <tr class="text-slate-700 dark:text-slate-300 transition-colors duration-200 hover:bg-slate-50 dark:hover:bg-slate-900">

                <td class="px-4 py-3">{{ $index + 1 }}</td>

                <td class="px-4 py-3 font-medium">{{ $submission->company_name }}</td>


            <td>
                @if($submission->start_date && $submission->finish_date)
                <div class="text-slate-700 dark:text-slate-100 text-sm">
                    {{ \Carbon\Carbon::parse($submission->start_date)->format('d M Y') }} -
                    {{ \Carbon\Carbon::parse($submission->finish_date)->format('d M Y') }}
                </div>
                @else
                <span class="text-slate-700 dark:text-slate-100 italic text-sm">Tidak diatur</span>
                @endif
            </td>

            <td>{{ $submission->submission_type === 'mandiri' ? 'Mandiri' : 'Mitra' }}</td>

                <td class="px-4 py-3">
                    <x-ui.badge :variant="$submission->getStatusVariant()" size="sm">
                        {{ $submission->getStatusLabel() }}
                    </x-ui.badge>
                </td>

                <td class="px-4 py-3">
                    <x-ui.actions :actions="[
                        [
                            'icon'  => 'info',
                            'color' => 'blue',
                            'label' => 'Lihat Detail',
                            'event' => '$dispatch(\'showDetail\', { submissionId: ' . $submission->id . ' })',
                        ],
                        [
                            'icon'  => 'edit',
                            'color' => $submission->canBeEdited() ? 'yellow' : 'gray',
                            'label' => $submission->canBeEdited() ? 'Edit Pengajuan' : 'Tidak dapat diubah',
                            'event' => $submission->canBeEdited()
                                ? 'redirectToEdit(' . $submission->id . ')'
                                : null,
                        ],
                        [
                            'icon'  => 'delete',
                            'color' => $submission->canBeDeleted() ? 'red' : 'gray',
                            'label' => $submission->canBeDeleted() ? 'Hapus Pengajuan' : 'Tidak dapat dihapus',
                            'event' => $submission->canBeDeleted()
                                ? 'confirmDelete(' . $submission->id . ')'
                                : null,
                        ],
                    ]" />
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-12 text-center">
                    <div class="flex flex-col items-center gap-3 text-slate-500 dark:text-slate-400">
                        <p class="font-medium">Belum ada pengajuan</p>
                        <p class="text-sm mt-1">Klik tombol "Buat Pengajuan Baru" untuk memulai</p>
                    </div>
                </td>
            </tr>
            @endforelse

        </x-ui.table>
    </div>

    @livewire('student.academic-service.submission.detail')

    {{-- Modal Konfirmasi Hapus --}}
    <dialog id="deleteModal" class="modal" wire:ignore.self>
        <div class="modal-box">
            <h3 class="font-bold text-lg text-error">Konfirmasi Hapus Pengajuan</h3>
            <p class="py-4">
                Yakin ingin menghapus pengajuan ke
                <span class="font-semibold text-error">{{ $selectedSubmission?->company_name ?? '' }}</span>?
            </p>
            <p class="text-sm text-slate-500">
                Tindakan ini tidak dapat dibatalkan. Semua dokumen yang terkait akan dihapus.
            </p>
            <div class="modal-action">
                <button class="btn btn-ghost" onclick="deleteModal.close()">Batal</button>
                <button
                    class="btn btn-error"
                    wire:click="delete"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="delete">Ya, Hapus</span>
                    <span wire:loading wire:target="delete" class="loading loading-spinner loading-sm"></span>
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
    $wire.on('open-delete-modal', () => deleteModal.showModal());
    $wire.on('close-delete-modal', () => deleteModal.close());
</script>
@endscript
