<?php

use App\Livewire\CreatePost;
use App\Livewire\Login;
use App\Livewire\Teacher\Dashboard;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', CreatePost::class);
Volt::route('/login', Login::class);

Route::middleware(['role:guru'])->group(function () {
    Route::get('/guru/dashboard', Dashboard::class);
});

// Route::middleware(['role:siswa'])->group(function () {
//     Route::get('/siswa/dashboard', SiswaDashboard::class);
// });

