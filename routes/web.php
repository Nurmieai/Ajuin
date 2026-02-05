<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\Dashboard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


Route::middleware('guest')->group( function(){
    Route::get('/register', \App\Livewire\Auth\Register::class)->name('register');
    Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
    Route::get('/', \App\Livewire\Auth\Login::class)->name('login');
});

Route::middleware('auth')->group( function()
{
    Route::get('/dashboard', Dashboard::class)->name('dashboard ');

    Route::middleware(['auth', 'teacher'])->group(function()
    {
        // Volt::route('/student-activation', );
    });
});


Route::get('/logout', function(Request $request)
{
      Auth::logout();
      $request->session()->invalidate();
      $request->session()->regenerateToken();
      return redirect('/login');
})->middleware('auth')->name('logout');
