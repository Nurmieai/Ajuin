<x-slot:title>
    Kelola Siswa
</x-slot:title>
<div>
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Kelola Siswa</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kelola status akun siswa</p>
        </div>
    </div>
{{-- Tabs --}}
<div role="tablist" class="tabs tabs-bordered">
    <button 
        wire:click="setTab('active')" 
        role="tab" 
        class="tab {{ $activeTab === 'active' ? 'tab-active' : '' }}">
        Siswa Aktif
    </button>

    <button 
        wire:click="setTab('archived')" 
        role="tab" 
        class="tab {{ $activeTab === 'archived' ? 'tab-active' : '' }}">
        Arsip
    </button>
</div>

<x-ui.table :columns="[
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

        <td class="font-medium">{{ $student->fullname }}</td>

        <td>{{ $student->nisn }}</td>

        <td>{{ $student->email }}</td>

        <td>{{ $student->major?->name ?? '-' }}</td>

        <td>
            @if($student->hasApprovedSubmission())
                <span class="badge badge-success badge-sm">Diterima</span>
            @else
                <span class="badge badge-ghost badge-sm">Belum</span>
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
                        'icon' => 'pause',
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
                        'icon' => 'edit',
                        'color' => 'green',
                        'event' => 'confirmRestore(' . $student->id . ')'
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

    @if ($showDetailModal && $selectedStudent)
        <div class="fixed inset-0 bg-black/50 dark:bg-black/70 flex items-center justify-center z-50"
             wire:click.self="closeDetail">
            @include('livewire.teacher.student-detail')
        </div>
    @endif

    {{-- Modal Deactivate --}}
    <dialog id="deactivateModal" class="modal" wire:ignore.self>
        <div class="modal-box">
            <h3 class="font-bold text-lg text-warning">Nonaktifkan Akun Siswa</h3>
            <p class="py-4">
                Yakin ingin menonaktifkan akun siswa
                <span class="font-semibold text-warning">
                    {{ $selectedStudent?->fullname ?? '' }}
                </span>?
            </p>
            <div class="alert alert-warning text-sm">
                <div>
                    <div>Siswa tidak akan bisa login</div>
                    <div class="text-xs">Histori PKL tetap tersimpan</div>
                </div>
            </div>

            <div class="modal-action">
                <button class="btn btn-ghost" onclick="deactivateModal.close()">
                    Batal
                </button>
                <button 
                    class="btn btn-warning"
                    wire:click="deactivate"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="deactivate">Ya, Nonaktifkan</span>
                    <span wire:loading wire:target="deactivate" class="loading loading-spinner loading-sm"></span>
                </button>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <dialog id="deleteModal" class="modal" wire:ignore.self>
        <div class="modal-box">
            <h3 class="font-bold text-lg text-error">Arsipkan Akun Siswa</h3>
            <p class="py-4">
                Yakin ingin mengarsipkan akun siswa
                <span class="font-semibold text-error">
                    {{ $selectedStudent?->fullname ?? '' }}
                </span>?
            </p>
            <div class="alert alert-error text-sm">
                <div>
                    <div>Siswa tidak akan bisa login</div>
                    <div class="text-xs">Histori PKL tetap tersimpan dan dapat dipulihkan</div>
                </div>
            </div>

            <div class="modal-action">
                <button class="btn btn-ghost" onclick="deleteModal.close()">
                    Batal
                </button>
                <button 
                    class="btn btn-error"
                    wire:click="delete"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="delete">Ya, Arsipkan</span>
                    <span wire:loading wire:target="delete" class="loading loading-spinner loading-sm"></span>
                </button>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <dialog id="restoreModal" class="modal" wire:ignore.self>
        <div class="modal-box">
            <h3 class="font-bold text-lg text-success">Pulihkan dari Arsip</h3>
            <p class="py-4">
                Yakin ingin memulihkan akun siswa
                <span class="font-semibold text-success">
                    {{ $selectedStudent?->fullname ?? '' }}
                </span> dari arsip?
            </p>
            <div class="alert alert-info text-sm">
                <span>Akun akan aktif kembali dan siswa bisa login</span>
            </div>

            <div class="modal-action">
                <button class="btn btn-ghost" onclick="restoreModal.close()">
                    Batal
                </button>
                <button 
                    class="btn btn-success"
                    wire:click="restore"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="restore">Ya, Pulihkan</span>
                    <span wire:loading wire:target="restore" class="loading loading-spinner loading-sm"></span>
                </button>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
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

    $wire.on('open-restore-modal', () => {
        restoreModal.showModal();
    });

    $wire.on('close-restore-modal', () => {
        restoreModal.close();
    });
</script>
@endscript