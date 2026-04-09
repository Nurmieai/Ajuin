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
        {{-- Tambahkan flex-1 agar search bar mengambil sisa ruang maksimal di mobile --}}
        <div class="flex-1 w-full">
            <x-ui.search wire:model.live.debounce.300ms="search" />
        </div>

        @role('teacher')
        {{-- Wrapper Tooltip DaisyUI (muncul di kiri saat mobile, di atas saat desktop) --}}
        <div class="tooltip tooltip-left sm:tooltip-top shrink-0" data-tip="Tambah Mitra">

            <a wire:navigate href="{{ route('partners.create') }}"
                class="btn btn-md flex items-center gap-2
                  bg-blue-600 hover:bg-blue-700
                  dark:bg-blue-500 dark:hover:bg-blue-400
                  text-white border-none">

                {{-- Icon selalu muncul --}}
                <x-ui.icon name="plus" size="sm" class="stroke-[3px]" />

                {{-- Teks hanya muncul di layar ukuran 'sm' (tablet/desktop) ke atas --}}
                <span class="hidden sm:inline-block font-medium">
                    Tambah Mitra
                </span>

            </a>
        </div>
        @endrole
    </div>

    <x-ui.table :columns="['Nama Mitra', 'Kuota', 'Kriteria','jurusan', 'Aksi']">
        @foreach($partners as $partner)
        <tr wire:key="{{ $partner->id }}"
            class="text-slate-700 dark:text-slate-300 
                   theme-transition
                   hover:bg-slate-50 dark:hover:bg-slate-900">
            <td>{{ $partner->name }}</td>
            <td>{{ $partner->quota }} orang</td>
            <td>{{ $partner->criteria ?? '-' }}</td>
            <td>{{ $partner->majors->pluck('name')->join(', ') }}</td>
            <td class="">
                @if(auth()->user()->hasRole('teacher'))
                <x-ui.actions :actions="[
                    [
                        'label' => 'Detail', 
                        'icon' => 'info', 
                        'color' => 'blue', 
                        // Gunakan $dispatch agar event terlempar secara global
                        'event' => '$dispatch(\'showDetail\', { id: ' . $partner->id . ' })'
                    ],
                    [
                        'label' => 'Edit', 
                        'icon' => 'edit', 
                        'color' => 'yellow',
                        'url' => route('partners.edit', $partner->id)],
                    [
                        'label' => 'Hapus',
                        'icon' => 'delete',
                        'color' => 'red',
                        'event' => 'confirmDelete(' . $partner->id . ')
                    '],
                ]" />
                @elseif(auth()->user()->hasRole('student'))
                <x-ui.actions :actions="[
                    [
                        'label' => 'Detail', 
                        'icon' => 'info', 
                        'color' => 'blue', 
                        'event' => '$dispatch(\'showDetail\', { id: ' . $partner->id . ' })'
                    ],
                    [
                        'label' => 'Ajukan PKL', 
                        'icon' => 'send', 
                        'color' => 'green', 
                        'event' => 'applyToPartner('.$partner->id.')'],
                ]" />
                @endif
            </td>
        </tr>
        @endforeach

    </x-ui.table>

    {{-- Modal Upload Berkas Sertifikat --}}
    <template x-teleport="body">
        <dialog
            id="certificate_upload_modal"
            class="modal backdrop-blur-sm"
            wire:ignore.self>

            <div class="modal-box w-full max-w-xl bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 shadow-xl rounded-xl p-0 overflow-hidden flex flex-col max-h-[90vh]">

                {{-- Header --}}
                <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-900/50 shrink-0">
                    <div>
                        <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100">Upload Berkas Sertifikat</h3>
                        <p class="text-xs text-slate-500 mt-1">Pastikan berkas valid (PDF/JPG/PNG) sebelum mengirim.</p>
                    </div>
                    <button type="button" onclick="document.getElementById('certificate_upload_modal').close()" wire:click="cancelApply" class="btn btn-sm btn-circle btn-ghost">✕</button>
                </div>

                {{-- Body --}}
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

                {{-- Footer --}}
                <div class="p-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-800 flex justify-end gap-3 shrink-0">
                    <button
                        type="button"
                        onclick="document.getElementById('certificate_upload_modal').close()"
                        wire:click="cancelApply"
                        class="btn px-8 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-200 border-none hover:bg-slate-300">
                        Batal
                    </button>

                    <button
                        form="submitAppForm"
                        type="submit"
                        class="btn px-8 bg-green-600 hover:bg-green-700 text-white border-none shadow-lg shadow-green-500/20"
                        wire:loading.attr="disabled"
                        wire:target="submitApplication">

                        <span wire:loading.remove wire:target="submitApplication">Kirim Pengajuan</span>
                        <span wire:loading wire:target="submitApplication" class="flex items-center gap-2">
                            <span class="loading loading-spinner loading-xs"></span> Mengirim...
                        </span>
                    </button>
                </div>
            </div>

            {{-- Backdrop Click --}}
            <form method="dialog" class="modal-backdrop">
                <button wire:click="cancelApply">close</button>
            </form>
        </dialog>
    </template>

    @script
    <script>
        // Listener untuk membuka modal upload
        window.addEventListener('open-certificate-modal', () => {
            document.getElementById('certificate_upload_modal').showModal();
        });

        // Listener untuk menutup modal setelah sukses
        window.addEventListener('close-certificate-modal', () => {
            document.getElementById('certificate_upload_modal').close();
        });
    </script>
    @endscript

    {{-- pagination --}}
    <div class="mx-auto justify-center">
        {{ $partners->links() }}
    </div>

    @livewire('partners.detail')

    {{-- Modal Konfirmasi Hapus --}}
    <x-ui.confirmation
        :open="$confirmingAction === 'delete'"
        type="danger"
        title="Hapus Mitra"
        message="Apakah Anda yakin ingin menghapus mitra ini? Data yang dihapus tidak dapat dikembalikan."
        confirmText="Ya, Hapus"
        cancelText="Batal"
        confirmAction="deleteConfirmed" />

    {{-- Modal Konfirmasi Pengajuan --}}
    <x-ui.confirmation
        :open="$confirmingAction === 'apply'"
        type="success"
        title="Ajukan PKL"
        message="Yakin ingin mengajukan PKL ke mitra ini?"
        confirmText="Ya, Ajukan"
        cancelText="Batal"
        confirmAction="confirmApply" />



</div>