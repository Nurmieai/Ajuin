<div class="bg-blue-400">
                @if (session()->has('success'))
                    <div class="alert alert-success shadow-sm text-sm">
                        {{ session('success') }}
                    </div>
                @endif
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
            @foreach ($students as $i => $student)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $student->fullname }}</td>
                <td>{{ $student->email }}</td>
                <td>
                    <button class="hover:bg-red-200" wire:click="approve({{ $student->id }})">
                        ACC
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
