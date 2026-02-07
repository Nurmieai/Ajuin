<x-slot:title>
    Mitra PKL
</x-slot:title>


<div class="flex flex-col gap-4">
    <div class="flex flex-row justify-between"><x-ui.search /> <a href="{{ route('partners.create') }}" class="btn">Tambah Mitra</a> </div>
    <x-ui.table :columns="['Nama Mitra', 'Kuota', 'Kriteria', 'Aksi']">
        @foreach($partners as $partner)
        <tr>
            <td class="font-medium text-slate-700">{{ $partner->name }}</td>
            <td>{{ $partner->quota }} orang</td>
            <td>{{ $partner->criteria ?? '-' }}</td>
            <td>
                {{-- Pengecekan Role menggunakan Spatie --}}
                @if(auth()->user()->hasRole('teacher'))
                <x-ui.actions :actions="[
                    ['label' => 'Detail', 'icon' => 'info', 'color' => 'blue', 'url' => route('partners.show', $partner->id)],
                    ['label' => 'Edit', 'icon' => 'edit', 'color' => 'yellow', 'url' => route('partners.edit', $partner->id)],
                    ['label' => 'Hapus', 'icon' => 'delete', 'color' => 'red', 'event' => 'confirmDelete('.$partner->id.')'],
                ]" />
                @elseif(auth()->user()->hasRole('student'))
                <x-ui.actions :actions="[
                    ['label' => 'Detail', 'icon' => 'info', 'color' => 'blue', 'url' => route('partners.show', $partner->id)],
                    ['label' => 'Ajukan PKL', 'icon' => 'send', 'color' => 'green', 'event' => 'applyToPartner('.$partner->id.')'],
                ]" />
                @endif
            </td>
        </tr>
        @endforeach
    </x-ui.table>
</div>