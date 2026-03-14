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
        <div class="flex justify between w-full">
            <x-ui.search class="mb-4" />
            {{-- Tabs --}}
            <div role="tablist" class="tabs tabs-bordered w-full flex justify-end content-end">
                <button
                    wire:click="setTab('active')"
                    role="tab"
                    class="tab {{ $activeTab === 'active' ? 'tab-active dark:bg-slate-900 border-x-1 border-t-1 border-slate-200 dark:border-slate-800 rounded-t-lg

' : '' }}">
                    Siswa Aktif
                </button>
                <button
                    wire:click="setTab('inactive')"
                    role="tab"
                    class="tab {{ $activeTab === 'inactive' ? 'tab-active dark:bg-slate-900 border-x-1 border-t-1 border-slate-200 dark:border-slate-800 rounded-t-lg' : '' }}">
                    Aktivasi Siswa
                </button>
                <button
                    wire:click="setTab('archived')"
                    role="tab"
                    class="tab {{ $activeTab === 'archived' ? 'tab-active dark:bg-slate-900 border-x-1 border-t-1 border-slate-200 dark:border-slate-800 rounded-t-lg' : '' }}">
                    Arsip
                </button>
            </div>
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

                <td class=" font-medium">{{ $student->fullname }}</td>

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
            <div class="alert alert-success text-sm">
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

    <dialog id="approveModal" class="modal" wire:ignore.self>
        <div class="modal-box">
            <h3 class="font-bold text-lg text-success">Aktifkan Akun Siswa</h3>
            <p class="py-4">
                Yakin ingin mengaktifkan akun siswa
                <span class="font-semibold text-success">
                    {{ $selectedStudent?->fullname ?? '' }}
                </span>?
            </p>
            <div class="alert alert-success text-sm">
                <span>Siswa akan bisa login kembali</span>
            </div>

            <div class="modal-action">
                <button class="btn btn-ghost" onclick="approveModal.close()">
                    Batal
                </button>
                <button
                    class="btn btn-success"
                    wire:click="approve"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="approve">Ya, Aktifkan</span>
                    <span wire:loading wire:target="approve" class="loading loading-spinner loading-sm"></span>
                </button>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <dialog id="rejectModal" class="modal" wire:ignore.self>
        <div class="modal-box">
            <h3 class="font-bold text-lg text-success">Tolak Akun siswa?</h3>
            <p class="py-4">
                Yakin ingin menghapus akun siswa
                <span class="font-semibold text-success">
                    {{ $selectedStudent?->fullname ?? '' }}
                </span>?
            </p>
            <div class="alert alert-error text-sm">
                <span>Akun siswa akan dihapus</span>
            </div>

            <div class="modal-action">
                <button class="btn btn-ghost" onclick="rejectModal.close()">
                    Batal
                </button>
                <button
                    class="btn btn-error"
                    wire:click="reject"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="reject">Ya, Hapus</span>
                    <span wire:loading wire:target="reject" class="loading loading-spinner loading-sm"></span>
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