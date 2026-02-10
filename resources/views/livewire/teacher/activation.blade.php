<div>
<x-slot:title>
    Aktivasi Akun
</x-slot:title>

    @if (session()->has('success'))
        <div class="alert alert-success shadow-sm text-sm">
            {{ session('success') }}
        </div>
    @endif
    <div  class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100">
        <table class="table w-full">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($students as $i => $student)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $student->fullname }}</td>
                        <td>{{ $student->email }}</td>
                        <td>
                            <button class="btn btn-primary"
                                    wire:click="confirmApprove({{ $student->id }})">
                                ACC
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-6 text-gray-500">
                            <div class="flex flex-col items-center gap-2">
                                <span class="text-sm">
                                    Belum ada siswa yang mendaftar
                                </span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <dialog id="approveModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Konfirmasi Aktivasi</h3>
        <p class="py-4">
            Yakin ingin mengaktifkan akun siswa ini?
        </p>

        <div class="modal-action">
            <button class="btn btn-ghost"
                    onclick="approveModal.close()">
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
