<div class="bg-blue-300">
    <form wire:submit.prevent="register">
    <input wire:model="fullname" placeholder="Nama lengkap">
    <input wire:model="username" placeholder="Username">
    <input wire:model="email" type="email" placeholder="Email">
    <input wire:model="nisn" placeholder="NISN">

    <select wire:model="major_id">
        <option value="">Pilih Jurusan</option>
        @foreach($majors as $major)
            <option value="{{ $major->id }}">{{ $major->name }}</option>
        @endforeach
    </select>

    <input wire:model="password" type="password" placeholder="Password">

    <button type="submit">Register</button>
</form>

</div>