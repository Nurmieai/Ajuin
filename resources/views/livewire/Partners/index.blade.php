<x-slot:title>
    Mitra PKL
</x-slot:title>

<div class="flex flex-col gap-4">

    <div class="flex flex-row justify-between items-center">
        <x-ui.search />

        @if(auth()->user()->hasRole('teacher'))
        <a href="{{ route('partners.create') }}"
            class="btn bg-blue-600 hover:bg-blue-700
                  dark:bg-blue-500 dark:hover:bg-blue-400
                  text-white border-none">
            Tambah Mitra
        </a>
        @endif
    </div>

    <x-ui.table :columns="['Nama Mitra', 'Kuota', 'Kriteria', 'Aksi']">
        @foreach($partners as $partner)
        <tr wire:key="{{ $partner->id }}" class="text-slate-700 dark:text-slate-300">
            <td>{{ $partner->name }}</td>
            <td>{{ $partner->quota }} orang</td>
            <td>{{ $partner->criteria ?? '-' }}</td>
            <td>
                @if(auth()->user()->hasRole('teacher'))
                <x-ui.actions :actions="[
                    ['label' => 'Detail', 'icon' => 'info', 'color' => 'blue', 'event' => 'showDetail(' . $partner->id . ')'],
                    ['label' => 'Edit', 'icon' => 'edit', 'color' => 'yellow', 'url' => route('partners.edit', $partner->id)],
                    ['label' => 'Hapus', 'icon' => 'delete', 'color' => 'red', 'event' => 'confirmDelete('.$partner->id.')'],
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

    {{-- MODAL DETAIL --}}
    @if($selectedPartner)
    <div class="fixed inset-0 bg-black/50 dark:bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-slate-950
                    border border-slate-200 dark:border-slate-800
                    rounded-xl shadow-2xl
                    w-full max-w-xl overflow-hidden
                    animate-in fade-in zoom-in duration-200">

            @livewire('partners.detail', ['partner' => $selectedPartner], key('detail-'.$selectedPartner->id))

        </div>
    </div>
    @endif

</div>