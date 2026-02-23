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
            'teacher' => 'Kelola Mitra PKL', 
            'student' => 'Daftar Mitra PKL']"
        :subtitle="[
            'teacher' => 'Kelola data mitra PKL, tambahkan mitra baru, atau perbarui informasi mitra yang sudah ada.',
            'student' => 'Temukan mitra PKL yang sesuai dengan minatmu. Cari berdasarkan nama atau bidang industri, lalu ajukan permohonan PKL.']" />

    <div class="flex flex-row gap-4 justify-between items-center">
        <x-ui.search />

        @role('teacher')
        <a href="{{ route('partners.create') }}"
            class="btn btn-md
                  bg-blue-600 hover:bg-blue-700
                  dark:bg-blue-500 dark:hover:bg-blue-400
                  text-white border-none">
            Tambah Mitra
        </a>
        @endrole
    </div>

    <x-ui.table :columns="['Nama Mitra', 'Kuota', 'Kriteria', 'Aksi']">
        @foreach($partners as $partner)
        <tr wire:key="{{ $partner->id }}"
            class="text-slate-700 dark:text-slate-300 
                   transition-colors duration-200 
                   hover:bg-slate-50 dark:hover:bg-slate-900">
            <td>{{ $partner->name }}</td>
            <td>{{ $partner->quota }} orang</td>
            <td>{{ $partner->criteria ?? '-' }}</td>
            <td class="">
                @if(auth()->user()->hasRole('teacher'))
                <x-ui.actions :actions="[
                    [
                        'label' => 'Detail', 
                        'icon' => 'info', 
                        'color' => 'blue', 
                        'event' => 'showDetail(' . $partner->id . ')
                    '],
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

    {{-- pagination --}}
    <div class="mx-auto justify-center">
        {{ $partners->links() }}
    </div>

    {{-- MODAL DETAIL --}}
    @if($selectedPartner)
    <div class="fixed inset-0 bg-black/50 dark:bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4"
        wire:click="close">
        <div class="bg-white dark:bg-slate-950
                    border border-slate-200 dark:border-slate-800
                    rounded-xl shadow-2xl
                    w-full max-w-xl overflow-hidden
                    animate-in fade-in zoom-in duration-200">

            @livewire('partners.detail', ['partner' => $selectedPartner], key('detail-'.$selectedPartner->id))

        </div>
    </div>
    @endif

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