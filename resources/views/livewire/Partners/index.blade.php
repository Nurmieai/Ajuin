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

    <div class="flex flex-row gap-4 justify-between items-center">
        <x-ui.search />

        @role('teacher')
        <a wire:navigate href="{{ route('partners.create') }}"
            class="btn btn-md
                  bg-blue-600 hover:bg-blue-700
                  dark:bg-blue-500 dark:hover:bg-blue-400
                  text-white border-none">

            Tambah Mitra
        </a>
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
                    ['label' => 'Detail', 'icon' => 'info', 'color' => 'blue', 'event' => 'showDetail(' . $partner->id . ')'],
                    ['label' => 'Ajukan PKL', 'icon' => 'send', 'color' => 'green', 'event' => 'applyToPartner('.$partner->id.')'],
                ]" />
                @endif
            </td>
        </tr>
        @endforeach

    </x-ui.table>

    <x-ui.modal
        :open="$showCertificateModal"
        maxWidth="max-w-xl"
        wire:click="$set('showCertificateModal', false)">
        <div class="p-6 space-y-6">

            {{-- Header --}}
            <div>
                <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-100">
                    Upload Berkas Sertifikat
                </h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    Pastikan semua berkas diupload sebelum mengajukan PKL.
                </p>
            </div>

            {{-- Form --}}
            <form wire:submit.prevent="submitApplication" class="space-y-4">
                {{-- HAPUS @csrf - tidak diperlukan di Livewire --}}

                <x-ui.input
                    name="industrial_visit"
                    label="Sertifikat Industrial Visit"
                    type="file"
                    wire:model.live="industrial_visit" />

                <x-ui.input
                    name="competency_test"
                    label="Sertifikat Competency Test"
                    type="file"
                    wire:model.live="competency_test" />

                <x-ui.input
                    name="spp_card"
                    label="SPP Card"
                    type="file"
                    wire:model.live="spp_card" />

                {{-- Footer --}}
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
                    <button
                        type="button"
                        wire:click="$set('showCertificateModal', false)"
                        class="btn btn-ghost">
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="btn bg-green-600 hover:bg-green-700 dark:bg-green-600 dark:hover:bg-green-700 text-white border-none"
                        wire:loading.attr="disabled"
                        wire:target="submitApplication">

                        <span wire:loading.remove wire:target="submitApplication">
                            Kirim Pengajuan
                        </span>
                        <span wire:loading wire:target="submitApplication">
                            Mengirim...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </x-ui.modal>

    {{-- pagination --}}
    <div class="mx-auto justify-center">
        {{ $partners->links() }}
    </div>

    {{-- index.blade.php --}}
    @livewire('partners.detail')

    <x-ui.confirmation
        :open="$confirmingAction === 'delete'"
        title="Hapus Mitra"
        message="Apakah Anda yakin ingin menghapus mitra ini? Data yang dihapus tidak dapat dikembalikan."
        confirmText="Ya, Hapus"
        cancelText="Batal"
        confirmAction="deleteConfirmed" />

    <x-ui.confirmation
        :open="$confirmingAction === 'apply'"
        title="Ajukan PKL"
        message="Yakin ingin mengajukan PKL ke mitra ini?"
        confirmText="Ya, Ajukan"
        cancelText="Batal"
        confirmAction="confirmApply" />

    <x-ui.toast />

</div>