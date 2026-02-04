<div>
<form wire:submit.prevent="login" class="bg-blue-300">
    <input wire:model="email" type="email" placeholder="Email">
    <input wire:model="password" type="password" placeholder="Password">
    <button type="submit" class="hover:bg-red-100">Login</button>

    @error('email')
        <div class="bg-red-400">{{ $message }}</div>
    @enderror
</form>
</div>