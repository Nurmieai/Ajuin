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
    <x-ui.search />
    <x-ui.table :columns="['Nama','Nama perusahaan','tanggal mulai','tanggal selesai','Status']">
        @forelse ($submissions as $submission)
        <tr class="hover:bg-base-200 transition-colors">
            <td class="font-medium">{{ $submission->user->fullname }}</td>
            <td>{{ $submission->company_name }}</td>
            <td>{{ $submission->start_date->format('d/m/Y') }}</td>
            <td>{{ $submission->finish_date->format('d/m/Y') }}</td>
            <td>
                <div class="flex gap-2">
                    <span class="badge badge-success">Diterima</span>
                </div>
            </td>
        </tr>
        @empty

        <tr>
            <td colspan="5" class="text-center py-8">
                <div class="flex flex-col items-center gap-3 text-slate-500 dark:text-slate-400">
                    <span class="text-sm font-medium">Belum ada pengajuan yang diterima</span>
                </div>
            </td>
        </tr>
        @endforelse
    </x-ui.table>
</div>