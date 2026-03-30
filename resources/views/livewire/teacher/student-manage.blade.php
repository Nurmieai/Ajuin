<x-slot:title>
    Kelola Siswa
</x-slot:title>
<div class="flex flex-col gap-4">
    <x-ui.breadcrumbs :items="[
        'Aktivasi Akun' => [
            'url' => route('teacher.activation'),
            'icon' => 'document-check'
        ]
    ]" />

    <x-ui.pageheader
        title="Kelola Siswa"
        subtitle="Kelola status akun siswa" />
    <div>
        <div class="flex flex-col md:flex-row md:justify-between md:items-end w-full gap-4 md:gap-0">
            <div class="w-full md:w-auto px-2 md:px-0">
                <x-ui.search wire:model.live.debounce.300ms="search" :isResponsive="false" class="w-full md:mb-4" />
            </div>
            <div role="tablist" class="tabs tabs-bordered flex justify-center md:justify-end -mb-[1px] relative z-10 w-full md:w-auto">
                <button
                    wire:click="setTab('active')"
                    role="tab"
                    title="Siswa Aktif"
                    class="tab flex-1 md:flex-none h-auto py-3 md:py-2 px-4 md:px-6
            {{ $activeTab === 'active' 
                ? 'tab-active bg-white dark:bg-slate-900 border-x border-t border-slate-200 dark:border-slate-800 rounded-t-lg' 
                : 'border-b-transparent' }}">
                    <div class="flex items-center justify-center gap-2">
                        <x-ui.icon name="users" size="sm" class="" />
                        <span class="hidden md:inline font-medium">Siswa Aktif</span>
                    </div>
                </button>

                <button
                    wire:click="setTab('inactive')"
                    role="tab"
                    title="Aktivasi Siswa"
                    class="tab flex-1 md:flex-none h-auto py-3 md:py-2 px-4 md:px-6
                    {{ $activeTab === 'inactive' 
                        ? 'tab-active bg-white dark:bg-slate-900 border-x border-t border-slate-200 dark:border-slate-800 rounded-t-lg' 
                        : 'border-b-transparent' }}">
                    <div class="flex items-center justify-center gap-2">
                        <x-ui.icon name="user-plus" size="sm" class="" />
                        <span class="hidden md:inline font-medium">Aktivasi Siswa</span>
                    </div>
                </button>

                <button
                    wire:click="setTab('archived')"
                    role="tab"
                    title="Arsip"
                    class="tab flex-1 md:flex-none h-auto py-3 md:py-2 px-4 md:px-6
            {{ $activeTab === 'archived' 
                ? 'tab-active bg-white dark:bg-slate-900 border-x border-t border-slate-200 dark:border-slate-800 rounded-t-lg' 
                : 'border-b-transparent' }}">
                    <div class="flex items-center justify-center gap-2">
                        <x-ui.icon name="archive" size="sm" class="" />
                        <span class="hidden md:inline font-medium">Arsip</span>
                    </div>
                </button>

            </div>
        </div>

        <x-ui.table
            :flatRight="$activeTab === 'archived'"
            :flatLeft="$activeTab === 'active'"
            :columns="[
            'No',
            'Nama',
            'NISN',
            'Email',
            'Jurusan',
            'Status PKL',
            'Aksi'
        ]">

            @forelse ($students as $index => $student)
            <tr class="hover:bg-slate-50 dark:hover:bg-slate-900 transition-colors">
                <td>{{ $index + 1 }}</td>

                <td class=" font-medium">{{ $student->fullname }}</td>

                <td>{{ $student->nisn }}</td>

                <td>{{ $student->email }}</td>

                <td>{{ $student->major?->name ?? '-' }}</td>

                <td>
                    @if($student->hasApprovedSubmission())
                    <x-ui.badge variant="success" size="sm">
                        Diterima
                    </x-ui.badge>
                    @else
                    <x-ui.badge variant="neutral" size="sm" class="opacity-60">
                        Belum
                    </x-ui.badge>
                    @endif
                </td>

                <td>
                    @php
                    $actions = [
                    [
                    'label' => 'Detail',
                    'icon' => 'info',
                    'color' => 'blue',
                    'event' => 'showDetail(' . $student->id . ')'
                    ]
                    ];

                    if ($activeTab === 'active') {
                    $actions[] = [
                    'label' => 'Nonaktifkan',
                    'icon' => 'exclamation-circle',
                    'color' => 'yellow',
                    'event' => 'confirmDeactivate(' . $student->id . ')'
                    ];

                    $actions[] = [
                    'label' => 'Arsipkan',
                    'icon' => 'archive',
                    'color' => 'red',
                    'event' => 'confirmDelete(' . $student->id . ')'
                    ];
                    }

                    if ($activeTab === 'archived') {
                    $actions[] = [
                    'label' => 'Pulihkan',
                    'icon' => 'arrow-up-circle',
                    'color' => 'green',
                    'event' => 'confirmRestore(' . $student->id . ')'
                    ];
                    }

                    if ($activeTab === 'inactive') {
                    $actions[] = [
                    'label' => 'Aktifkan',
                    'icon' => 'check',
                    'color' => 'green',
                    'event' => 'confirmApprove(' . $student->id . ')'
                    ];
                    $actions[] = [
                    'label' => 'Tolak Aktivasi',
                    'icon' => 'x-mark',
                    'color' => 'red',
                    'event' => 'confirmReject(' . $student->id . ')'
                    ];
                    }
                    @endphp

                    <x-ui.actions :actions="$actions" />
                </td>
            </tr>

            @empty
            <tr>
                <td colspan="7" class="text-center py-12">
                    <div class="flex flex-col items-center gap-3 text-slate-500 dark:text-slate-400">
                        <p class="font-medium">
                            @if($activeTab === 'active')
                            Belum ada siswa aktif
                            @else
                            Belum ada siswa di arsip
                            @endif
                        </p>
                    </div>
                </td>
            </tr>
            @endforelse

        </x-ui.table>

    </div>


    @include('livewire.teacher.student-detail')

    {{-- Modal Deactivate --}}
    <x-ui.confirmation
        :open="$isDeactivateOpen"
        title="Nonaktifkan Akun Siswa"
        confirmText="Ya, Nonaktifkan"
        confirmAction="deactivate"
        type="warning">
        <x-slot:message>
            Yakin ingin menonaktifkan akun siswa
            <span class="font-semibold text-slate-900 dark:text-white">{{ $selectedStudent?->fullname ?? '' }}</span>?
        </x-slot:message>

        <div class="space-y-1 p-3 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-100 dark:border-yellow-800">
            <div class="text-sm font-medium text-yellow-800 dark:text-yellow-500">Siswa tidak akan bisa login</div>
            <div class="text-xs text-yellow-700 dark:text-yellow-600">Histori PKL tetap tersimpan</div>
        </div>
    </x-ui.confirmation>

    <x-ui.confirmation
        :open="$isDeleteOpen"
        title="Arsipkan Akun Siswa"
        confirmText="Ya, Arsipkan"
        confirmAction="delete"
        type="danger">
        <x-slot:message>
            Yakin ingin mengarsipkan akun siswa
            <span class="font-semibold text-slate-900 dark:text-white">{{ $selectedStudent?->fullname ?? '' }}</span>?
        </x-slot:message>

        <div class="space-y-1 p-3 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800">
            <div class="text-sm font-medium text-red-800 dark:text-red-500">Siswa tidak akan bisa login</div>
            <div class="text-xs text-red-700 dark:text-red-600">Histori PKL tetap tersimpan dan dapat dipulihkan</div>
        </div>
    </x-ui.confirmation>

    <x-ui.confirmation
        :open="$isRestoreOpen"
        title="Pulihkan dari Arsip"
        confirmText="Ya, Pulihkan"
        confirmAction="restore"
        type="success">
        <x-slot:message>
            Yakin ingin memulihkan akun siswa
            <span class="font-semibold text-slate-900 dark:text-white">{{ $selectedStudent?->fullname ?? '' }}</span> dari arsip?
        </x-slot:message>

        <x-ui.badge variant="success" size="sm">
            Akun akan aktif kembali dan siswa bisa login
        </x-ui.badge>
    </x-ui.confirmation>

    <x-ui.confirmation
        :open="$isApproveOpen"
        title="Aktifkan Akun Siswa"
        confirmText="Ya, Aktifkan"
        confirmAction="approve"
        type="success">
        <x-slot:message>
            Yakin ingin mengaktifkan akun siswa
            <span class="font-semibold text-slate-900 dark:text-white">{{ $selectedStudent?->fullname ?? '' }}</span>?
        </x-slot:message>

        <x-ui.badge variant="success" size="sm">
            Siswa akan bisa login kembali
        </x-ui.badge>
    </x-ui.confirmation>

    <x-ui.confirmation
        :open="$isRejectOpen"
        title="Tolak Akun Siswa?"
        confirmText="Ya, Hapus"
        confirmAction="reject"
        type="danger">
        <x-slot:message>
            Yakin ingin menghapus akun siswa
            <span class="font-semibold text-slate-900 dark:text-white">{{ $selectedStudent?->fullname ?? '' }}</span>?
        </x-slot:message>

        <x-ui.badge variant="danger" size="sm">
            Akun siswa akan dihapus permanen
        </x-ui.badge>
    </x-ui.confirmation>

    {{-- pagination --}}
    <div class="mx-auto justify-center">
        {{ $students->links() }}
    </div>
</div>

@script
<script>
    $wire.on('open-deactivate-modal', () => {
        deactivateModal.showModal();
    });

    $wire.on('close-deactivate-modal', () => {
        deactivateModal.close();
    });

    $wire.on('open-delete-modal', () => {
        deleteModal.showModal();
    });

    $wire.on('close-delete-modal', () => {
        deleteModal.close();
    });

    $wire.on('open-approve-modal', () => {
        approveModal.showModal();
    });

    $wire.on('close-approve-modal', () => {
        approveModal.close();
    });

    $wire.on('open-reject-modal', () => {
        rejectModal.showModal();
    });

    $wire.on('close-reject-modal', () => {
        rejectModal.close();
    });

    $wire.on('open-restore-modal', () => {
        restoreModal.showModal();
    });

    $wire.on('close-restore-modal', () => {
        restoreModal.close();
    });
</script>
@endscript