<div class="bg-blue-300">
    <form wire:submit.prevent="register">
    <input wire:model="fullname" placeholder="Nama lengkap">
        @error('fullname')
        <div class="bg-red-400">{{ $message }}</div>
        @enderror  
    <input wire:model="username" placeholder="Username">
    @error('username')
        <div class="bg-red-400">{{ $message }}</div>
    @enderror  
    <input wire:model="email" type="email" placeholder="Email">
    @error('email')
        <div class="bg-red-400">{{ $message }}</div>
    @enderror  
    <input wire:model="nisn" placeholder="NISN">
    @error('nisn')
        <div class="bg-red-400">{{ $message }}</div>
    @enderror  

    <select wire:model="major_id">
        <option value="">Pilih Jurusan</option>
        @foreach($majors as $major)
            <option value="{{ $major->id }}">{{ $major->name }}</option>
        @endforeach
    </select>
    @error('major_id')
        <div class="bg-red-400">{{ $message }}</div>
    @enderror  

    <input wire:model.defer="password" type="password" placeholder="Password">
    @error('password')
        <div class="bg-red-400">{{ $message }}</div>
    @enderror  

    <input wire:model.defer="password_confirmation" type="password" placeholder="Konfirmasi Password">
    @error('passsword_confirmation')
        <div class="bg-red-400">{{ $message }}</div>
    @enderror  

    <button type="submit">Register</button>
</form>


</div>