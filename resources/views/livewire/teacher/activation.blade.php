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
    @if (session()->has('success'))
    <div class="alert alert-success shadow-sm text-sm">
        {{ session('success') }}
    </div>
    @endif

    <x-ui.search />

    <x-ui.table :columns="['No','Nama','Email','Aksi']">

        @forelse ($students as $i => $student)
        <tr>
            <td class="px-4 py-3">{{ $i + 1 }}</td>
            <td class="px-4 py-3">{{ $student->fullname }}</td>
            <td class="px-4 py-3">{{ $student->email }}</td>
            <td class="px-4 py-3">
                <button
                    class="btn btn-primary"
                    wire:click="confirmApprove({{ $student->id }})">
                    ACC
                </button>
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

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('open-approve-modal', () => {
                approveModal.showModal();
            });
        });
    </script>
</div>