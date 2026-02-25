<x-slot:title>
    Dashboard
</x-slot:title>

<div class="flex flex-col gap-4">

    <!-- Welcome Card -->
    <div class="flex flex-col sm:flex-row items-center justify-center 
                gap-1 sm:gap-2
                text-xl sm:text-2xl font-bold
                w-full
                py-6 sm:h-[120px]
                text-center
                rounded-lg
                border
                text-slate-700 dark:text-slate-200
                border-slate-200 dark:border-slate-700
                bg-white dark:bg-slate-900">

        <h1>Selamat Datang,</h1>
        <h1 class="break-words text-blue-600 dark:text-blue-400">
            {{ ucwords(auth()->user()->username) }}
        </h1>
    </div>


    <!-- Table Section -->
    <div class="flex flex-col gap-2">

        <!-- Table wrapper biar bisa scroll di mobile -->
        <div class="w-full overflow-x-auto">
            <x-ui.table :columns="['No', 'Nama', 'Jurusan', 'Status']">

                @forelse ($submissions as $index => $submission)
                <tr class="transition-colors duration-200 
                   hover:bg-slate-50 dark:hover:bg-slate-900">

                    <td class="px-4 py-3">
                        {{ $index + 1 }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $submission->user->username }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $submission->user->major->name ?? '-' }}
                    </td>

                    <td class="px-4 py-3">
                        <span class="badge {{ $submission->getStatusBadgeClass() }} badge-sm md:badge-md">
                            {{ $submission->getStatusLabel() }}
                        </span>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4">
                        Tidak ada data submission
                    </td>
                </tr>
                @endforelse

            </x-ui.table>
        </div>
    </div>
</div>