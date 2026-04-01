<x-slot:title>
    Aktivasi Akun
</x-slot:title>

<div class="flex flex-col gap-4">
    <x-ui.breadcrumbs :items="[
        'Aktivasi Akun' => [
            'url' => route('teacher.activation'),
            'icon' => 'document-check'
        ]
    ]" />

    <x-ui.pageheader
        title="Kelola Aktivasi Akun Siswa"
        subtitle="Kelola aktivasi akun siswa yang belum aktif." />
    <div class="flex flex-row gap-4 justify-between items-center">
        <x-ui.search wire:model.live.debounce.300ms="search" />
        <a wire:navigate href="{{ route('teacher.students-manage') }}"
            class="btn btn-md
                  bg-blue-600 hover:bg-blue-700
                  dark:bg-blue-500 dark:hover:bg-blue-400
                  text-white border-none">
            Kelola siswa
        </a>
    </div>
    <x-ui.table :columns="['No','Nama','Email','Aksi']">

        @forelse ($students as $i => $student)
        <tr class="text-slate-700 dark:text-slate-300 
                   transition-colors duration-200 
                   hover:bg-slate-50 dark:hover:bg-slate-900">
            <td class="px-4 py-3">{{ $i + 1 }}</td>
            <td class="px-4 py-3">{{ $student->fullname }}</td>
            <td class="px-4 py-3">{{ $student->email }}</td>
            <td class="px-4 py-3">
                <div class="flex justify-center gap-2">
                    <div class="tooltip" data-tip="Aktifkan akun">
                        <button
                            class="btn btn-sm btn-success btn-circle"
                            wire:click="confirmApprove({{ $student->id }})">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-4 h-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                    </div>
                    <div class="tooltip" data-tip="Tolak akun">
                        <button
                            class="btn btn-sm btn-error btn-circle"
                            wire:click="confirmReject({{ $student->id }})">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-4 h-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4"
                class="text-center py-6 text-slate-500 dark:text-slate-400">
                <div class="flex flex-col items-center gap-2">
                    <span class="text-sm">
                        Belum ada siswa yang mendaftar
                    </span>
                </div>
            </td>
        </tr>
        @endforelse

    </x-ui.table>

    {{-- pagination --}}
    <div class="mx-auto justify-center">
        {{ $students->links() }}
    </div>

    <dialog id="approveModal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Konfirmasi Aktivasi</h3>
            <p class="py-4">
                Yakin ingin mengaktifkan akun siswa ini?
            </p>

            <div class="modal-action">
                <button class="btn btn-ghost" onclick="approveModal.close()">
                    Batal
                </button>

                <button class="btn btn-success"
                    wire:click="approve"
                    onclick="approveModal.close()">
                    Ya, Aktifkan
                </button>
            </div>
        </div>
    </dialog>
    <dialog id="rejectModal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Konfirmasi Penolakan</h3>
            <p class="py-4">
                Apakah Anda yakin ingin menolak akun siswa ini?
                Akun akan dihapus dari daftar pendaftaran.
            </p>
            <div class="modal-action">
                <button class="btn btn-ghost" onclick="rejectModal.close()">
                    Batal
                </button>
                <button class="btn btn-error"
                    wire:click="reject"
                    onclick="rejectModal.close()">
                    Ya, Tolak
                </button>
            </div>
        </div>
    </dialog>

    <x-ui.toast />

    <script>
        document.addEventListener('livewire:init', () => {

            Livewire.on('open-approve-modal', () => {
                document.getElementById('approveModal').showModal();
            });

            Livewire.on('open-reject-modal', () => {
                document.getElementById('rejectModal').showModal();
            });

        });
    </script>
</div>