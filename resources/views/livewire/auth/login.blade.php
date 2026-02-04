<div>
<form wire:submit.prevent="login" class="bg-blue-300">
    <input wire:model="email" type="email" placeholder="Email">
    @error('email')
        <div class="bg-red-400">{{ $message }}</div>
    @enderror
    <input wire:model="password" type="password" placeholder="Password">
    @error('password')
        <div class="bg-red-400">{{ $message }}</div>
    @enderror
    <button type="submit" class="hover:bg-red-100">Login</button>

</form>
@if (session('message'))
<div class="bg-yellow-400">{{ session('message') }}</div>
@endif
</div>