<x-slot:title>
    Mitra PKL
</x-slot:title>

<div class="flex flex-col gap-4">
    <x-ui.breadcrumbs :items="[
        'Mitra' => [
            'url' => route('partners.index'),
            'icon' => 'academic-cap' 
        ],
    ]" />

    <x-ui.pageheader
        :title="[
            'teacher' => 'Mitra PKL', 
            'student' => 'Mitra PKL']"
        :subtitle="[
            'teacher' => 'Kelola data mitra PKL, tambahkan mitra baru, atau perbarui informasi mitra yang sudah ada.',
            'student' => 'Temukan mitra PKL yang sesuai dengan minatmu. Cari berdasarkan nama atau bidang industri, lalu ajukan permohonan PKL.']" />

    <div class="flex flex-row gap-4 justify-between items-center w-full">
        <div class="flex-1 w-full h-[-webkit-fill-available]">
            <x-ui.search wire:model.live.debounce.600ms="search" placeholder="Cari Mitra,Jurusan, atau Kriteria" />
        </div>

        <div class="hidden lg:flex justify-center items-center gap-4 max-w-2xl text-slate-700 dark:text-slate-300 h-full">
            <span class="hidden sm:inline-block leading-none text-sm">periode</span>
            <x-ui.input
                wire:model.live="startDate"
                name="start_date"
                type="date" />
            <span class="hidden sm:inline-block leading-none text-sm">s/d</span>
            <x-ui.input
                wire:model.live="endDate"
                name="finish_date"
                type="date" />
        </div>

        @role('teacher')
        <div class="tooltip tooltip-left sm:tooltip-top shrink-0" data-tip="Tambah Mitra">
            <a wire:navigate href="{{ route('partners.create') }}"
                class="h-fit px-4 py-2 btn btn-md flex items-center gap-2
                  bg-blue-600 hover:bg-blue-700
                  dark:bg-blue-500 dark:hover:bg-blue-400
              text-white border-none">
                <x-ui.icon name="plus" size="sm" class="stroke-[3px]" />
                <span class="hidden sm:inline-block font-medium">
                    Tambah Mitra
                </span>
            </a>
        </div>
        @endrole
    </div>

    <div class="flex lg:hidden flex-col sm:flex-row justify-center items-start md:items-center gap-4 max-w-2xl text-slate-700 dark:text-slate-300 h-full">
        <span class="ms-4 text-sm leading-none inline-block">periode</span>
        <x-ui.input
            wire:model.live="startDate"
            name="start_date"
            type="date" />
        <span class="ms-4 md:ms-0 text-sm leading-none inline-block">s/d</span>
        <x-ui.input
            wire:model.live="endDate"
            name="finish_date"
            type="date" />
    </div>

    <x-ui.table :columns="['Nama Mitra', 'Rating', 'Kuota', 'Jurusan', 'Periode', 'Aksi']">
        @forelse($partners as $partner)
        <tr wire:key="partner-{{ $partner->id }}"
            class="text-slate-700 dark:text-slate-300 
                   theme-transition
                   hover:bg-slate-50 dark:hover:bg-slate-900">

            <td class="font-medium text-slate-900 dark:text-white">
                {{ $partner->name }}
            </td>

            <td>
                <div class="flex items-center gap-1">
                    @if($partner->reviews_avg_rating > 0)
                    <x-ui.icon name="star" size="xs" class="text-yellow-500 fill-yellow-500" />
                    <span class="font-semibold text-slate-900 dark:text-white">
                        {{ number_format($partner->reviews_avg_rating, 1) }}
                    </span>
                    @else
                    <x-ui.badge variant="neutral" size="xs">
                        Belum ada rating
                    </x-ui.badge>
                    @endif
                </div>
            </td>

            <td class="text-slate-700 dark:text-slate-100">
                {{ $partner->quota }} orang
            </td>

            <td>
                <span class="text-slate-700 dark:text-slate-100">
                    {{ $partner->majors->pluck('name')->implode(', ') }}
                </span>
            </td>

            <td>
                @if($partner->start_date && $partner->finish_date)
                <div class="text-slate-700 dark:text-slate-100 text-sm">
                    {{ \Carbon\Carbon::parse($partner->start_date)->format('d M Y') }} -
                    {{ \Carbon\Carbon::parse($partner->finish_date)->format('d M Y') }}
                </div>
                @else
                <span class="text-slate-700 dark:text-slate-100 italic text-sm">Tidak diatur</span>
                @endif
            </td>

            <td class="w-1/5">
                @php
                $actions = [];
                $actions[] = [
                'label' => 'Detail',
                'icon' => 'info',
                'color' => 'blue',
                'event' => '$dispatch(\'showDetail\', { id: ' . $partner->id . ' })'
                ];

                if(auth()->user()->hasRole('teacher')) {
                $actions[] = [
                'label' => 'Edit',
                'icon' => 'edit',
                'color' => 'yellow',
                'url' => route('partners.edit', $partner->id)
                ];
                $actions[] = [
                'label' => 'Hapus',
                'icon' => 'delete',
                'color' => 'red',
                'event' => 'confirmDelete(' . $partner->id . ')'
                ];
                } elseif(auth()->user()->hasRole('student')) {
                $actions[] = [
                'label' => 'Ajukan PKL',
                'icon' => 'send',
                'color' => 'green',
                'event' => 'applyToPartner(' . $partner->id . ')'
                ];
                }
                @endphp
                <x-ui.actions :actions="$actions" />
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center py-12">
                <div class="flex flex-col items-center gap-2">
                    <x-ui.icon name="archive" size="lg" class="text-slate-300 dark:text-slate-700" />
                    <p class="text-slate-500 dark:text-slate-400">Tidak ada mitra ditemukan sesuai kriteria.</p>
                </div>
            </td>
        </tr>
        @endforelse
    </x-ui.table>

    {{-- Modal Sertifikat --}}
    <template x-teleport="body">
        <dialog id="certificate_upload_modal" class="modal backdrop-blur-sm" wire:ignore.self>
            <div class="modal-box w-full max-w-xl bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 shadow-xl rounded-xl p-0 overflow-hidden flex flex-col max-h-[90vh]">
                <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-900/50 shrink-0">
                    <div>
                        <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100">Upload Berkas Sertifikat</h3>
                        <p class="text-xs text-slate-500 mt-1">Pastikan berkas valid (PDF/JPG/PNG) sebelum mengirim.</p>
                    </div>
                    <button type="button" onclick="document.getElementById('certificate_upload_modal').close()" wire:click="cancelApply" class="btn btn-sm btn-circle btn-ghost">✕</button>
                </div>

                <div class="p-8 space-y-6 overflow-y-auto">
                    <form id="submitAppForm" wire:submit.prevent="submitApplication" class="space-y-5">
                        <x-ui.input
                            name="industrial_visit"
                            label="Sertifikat Kunjungan Industri"
                            type="file"
                            wire:model.live="industrial_visit" />

                        <x-ui.input
                            name="competency_test"
                            label="Sertifikat UKK (Uji Kompetensi Keahlian)"
                            type="file"
                            wire:model.live="competency_test" />

                        <x-ui.input
                            name="spp_card"
                            label="Kartu SPP"
                            type="file"
                            wire:model.live="spp_card" />
                    </form>
                </div>

                <div class="p-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-800 flex justify-end gap-3 shrink-0">
                    <button type="button" onclick="document.getElementById('certificate_upload_modal').close()" wire:click="cancelApply" class="btn px-8 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-200 border-none hover:bg-slate-300">Batal</button>
                    <button form="submitAppForm" type="submit" class="btn px-8 bg-green-600 hover:bg-green-700 text-white border-none shadow-lg shadow-green-500/20" wire:loading.attr="disabled" wire:target="submitApplication">
                        <span wire:loading.remove wire:target="submitApplication">Kirim Pengajuan</span>
                        <span wire:loading wire:target="submitApplication" class="flex items-center gap-2">
                            <span class="loading loading-spinner loading-xs"></span> Mengirim...
                        </span>
                    </button>
                </div>
            </div>
            <form method="dialog" class="modal-backdrop">
                <button wire:click="cancelApply">close</button>
            </form>
        </dialog>
    </template>

    @script
    <script>
        window.addEventListener('open-certificate-modal', () => {
            document.getElementById('certificate_upload_modal').showModal();
        });
        window.addEventListener('close-certificate-modal', () => {
            document.getElementById('certificate_upload_modal').close();
        });
    </script>
    @endscript

    <div class="justify-center">
        {{ $partners->links() }}
    </div>

    @livewire('partners.detail')

    <x-ui.confirmation
        :open="$confirmingAction === 'delete'"
        type="danger"
        title="Hapus Mitra"
        message="Apakah Anda yakin ingin menghapus mitra ini? Data yang dihapus tidak dapat dikembalikan."
        confirmText="Ya, Hapus"
        cancelText="Batal"
        confirmAction="deleteConfirmed" />

    <x-ui.confirmation
        :open="$confirmingAction === 'apply'"
        type="success"
        title="Ajukan PKL"
        message="Yakin ingin mengajukan PKL ke mitra ini?"
        confirmText="Ya, Ajukan"
        cancelText="Batal"
        confirmAction="confirmApply" />
</div>