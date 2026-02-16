<x-slot:title>
    Pengajuan PKL
</x-slot:title>


    @if (session()->has('success'))
    <div class="alert alert-success shadow-sm text-sm">
        {{ session('success') }}
    </div>
    @endif

<div class="pt-2">
    <x-ui.table :columns="['Nama Siswa', 'Nama Mitra', 'Tanggal Mulai', 'Tanggal Selesai', 'Aksi']">
        @forelse ($submissions as $submission)
            <tr>
                <td>{{ $submission->user->fullname }}</td>
                <td>{{ $submission->company_name }}</td>
                <td>{{ $submission->start_date->format('Y-m-d')}}</td>
                <td>{{ $submission->finish_date->format('Y-m-d') }}</td>
                <td>
                    {{-- <x-ui.actions :actions="[
                        ['label' => 'Detail', 'icon' => 'info', 'color' => 'blue'],
                        ['label' => 'Approve', 'icon' => 'edit', 'event' => 'confirmApprove({{ $submission->id }})']
                    ]"/> --}}
                <button
                    class="btn btn-success"
                    wire:click="confirmApprove({{ $submission->id }})">
                    Terima
                </button>
                <button
                    class="btn btn-error"
                    wire:click="confirmReject({{ $submission->id }})">
                    Tolak
                </button>
                <button
                    class="btn btn-primary"
                    wire:click="">
                    Detail
                </button>
                </td>
            </tr>
            @empty
        <tr>
            <td colspan="4"
                class="text-center py-6 text-slate-500 dark:text-slate-400">
                <div class="flex flex-col items-center gap-2">
                    <span class="text-sm">
                        Belum ada Pengajuan
                    </span>
                </div>
            </td>
        </tr>
        @endforelse
    </x-ui.table>

   <dialog id="approveModal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Konfirmasi Terima Pengajuan</h3>
            <p class="py-4">
                Yakin ingin terima pengajuan?
            </p>

            <div class="modal-action">
                <button class="btn btn-ghost" onclick="approveModal.close()">
                    Batal
                </button>

                <button class="btn btn-success"
                    wire:click="approve"
                    onclick="approveModal.close()">
                    Ya, Terima
                </button>
            </div>
        </div>
    </dialog>

   <dialog id="rejectModal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Konfirmasi Tolak Pengajuan</h3>
            <p class="py-4">
                Yakin ingin tolak pengajuan?
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

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('open-approve-modal', () => {
                approveModal.showModal();
            });
        });
        document.addEventListener('livewire:init', () => {
            Livewire.on('open-reject-modal', () => {
                rejectModal.showModal();
            });
        });
    </script>
</div>
