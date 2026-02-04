<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name', 'Laravel App') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-gray-400">
        <div class="flex justify-center items-center min-h-screen">
        <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-xs border p-4 justify-center text-center">
        <form wire:submit.prevent='login'>
            <legend class="fieldset-legend">Login</legend>
            <label class="label">Email</label>
            <input wire:model='email' type="email" class="input" placeholder="Email" />
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
            
            <label class="label">Password</label>
            <input wire:model='password' type="password" class="input" placeholder="Password" />
            
            <button class="btn btn-neutral mt-4" type="submit">Login</button>
        </form>
        </fieldset>
        </div>
        @livewireScripts
</body>

</html>
