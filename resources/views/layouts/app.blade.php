<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laravel Livewire</title>

    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body class="bg-gray-100">

    {{ $slot }}

    @livewireScripts
</body>
</html>
