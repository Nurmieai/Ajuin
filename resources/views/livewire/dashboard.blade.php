<x-slot:title>Dashboard</x-slot:title>

<div class="flex flex-col gap-4 transition-all duration-200">

    <!-- Welcome Card -->
    <div class="flex flex-col sm:flex-row items-center justify-center 
                gap-1 sm:gap-2
                text-xl sm:text-2xl font-bold
                w-full
                py-6 sm:h-[120px]
                text-center
                rounded-lg
                border
                inset-shadow-sm
                text-slate-700 dark:text-slate-200
                border-slate-200 dark:border-slate-700
                bg-slate-50 dark:bg-slate-900
                theme-transition">

        <h1 class="text-sm md:text-2xl font-bold 
                  text-slate-800 dark:text-slate-100">Selamat Datang,</h1>
        <h1 class="text-sm md:text-2xl font-bold
                   break-words text-blue-600 dark:text-blue-400">
            {{ ucwords(auth()->user()->username) }}
        </h1>
    </div>


    @hasrole('teacher')
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <x-ui.card
            title="Siswa Teraktivasi"
            :value="$this->totalActiveStudents"
            icon="check-circle"
            color="green" />

        <x-ui.card
            title="Jumlah Pengajuan Siswa"
            :value="$this->totalSubmissions"
            icon="document"
            color="blue" />

        <x-ui.card
            title="Mitra tersedia"
            :value="$this->totalPartners"
            icon="users"
            color="purple" />
    </div>
    @endhasrole

    {{-- STUDENT CARDS --}}
    @hasrole('student')
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <x-ui.card
            title="Mitra Tersedia"
            :value="$this->availablePartners"
            icon="building"
            color="indigo" />

        <x-ui.card
            title="Pengajuan PKL"
            :value="$this->mySubmissions"
            icon="clipboard"
            color="orange" />
    </div>
    @endhasrole

    <!-- Table Section -->
    @hasrole('student')
    <div class="flex flex-col gap-2">
        <div class="w-full overflow-x-auto">
            <x-ui.table :columns="['No', 'Nama Perusahaan', 'Status']">
                @forelse ($this->submissions as $index => $submission)
                <tr class="transition-colors duration-200 hover:bg-slate-50 dark:hover:bg-slate-900">
                    <td class="px-4 py-3">{{ $index + 1 }}</td>
                    <td class="px-4 py-3">{{ $submission->company_name }}</td>
                    <td class="px-4 py-3">
                        <x-ui.badge :variant="$submission->getStatusVariant()" size="sm">
                            {{ $submission->getStatusLabel() }}
                        </x-ui.badge>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4">Tidak ada data submission</td>
                </tr>
                @endforelse
            </x-ui.table>
        </div>
    </div>
    @endhasrole
    @hasrole('teacher')
    <div class="flex flex-col gap-2">
        <div class="w-full overflow-x-auto">
            <x-ui.table :columns="['No', 'Nama siswa','Nama Perusahaan', 'Status']">
                @forelse ($this->submissions as $index => $submission)
                <tr class="transition-colors duration-200 hover:bg-slate-50 dark:hover:bg-slate-900">
                    <td class="px-4 py-3">{{ $index + 1 }}</td>
                    <td class="px-4 py-3">{{ $submission->user->username }}</td>
                    <td class="px-4 py-3">{{ $submission->company_name }}</td>
                    <td class="px-4 py-3">
                        <x-ui.badge :variant="$submission->getStatusVariant()" size="sm">
                            {{ $submission->getStatusLabel() }}
                        </x-ui.badge>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4">Tidak ada data submission</td>
                </tr>
                @endforelse
            </x-ui.table>
        </div>
    </div>
    @endhasrole

</div>