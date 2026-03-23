<x-slot:title>
    Bank PKL
</x-slot:title>

<div class="flex flex-col gap-4">
    <x-ui.breadcrumbs :items="[
        'Bank PKL' => [
            'url' => route('bankPKL'),
            'icon' => 'archive'
        ],
    ]" />

    <x-ui.pageheader
        title="Bank PKL"
        :subtitle="[
            'teacher' => 'Daftar pengajuan PKL siswa yang sudah diterima.',
            'student' => 'Daftar pengajuan PKL yang sudah diterima.']" />

    <x-ui.search />

    <x-ui.table :columns="['Nama','Nama perusahaan','tanggal mulai','tanggal selesai','Status']">
        @forelse ($submissions as $submission)
        <tr class="text-slate-700 dark:text-slate-300 
                  theme-transition
                   hover:bg-slate-50 dark:hover:bg-slate-900">
            <td class="font-medium">{{ $submission->user->fullname }}</td>
            <td>{{ $submission->company_name }}</td>
            <td>{{ $submission->start_date->format('d/m/Y') }}</td>
            <td>{{ $submission->finish_date->format('d/m/Y') }}</td>
            <td>
                <x-ui.badge :variant="$submission->getStatusVariant()" size="sm">
                    {{ $submission->getStatusLabel() }}
                </x-ui.badge>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center py-8 text-slate-500">
                Belum ada data pengajuan.
            </td>
        </tr>
        @endforelse
    </x-ui.table>

    {{-- pagination --}}
    <div class="mx-auto justify-center">
        {{ $submissions->links() }}
    </div>
</div>