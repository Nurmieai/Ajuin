<?php
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;


Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
Route::get('/rgister', \App\Livewire\Auth\Register::class)->name('register');
