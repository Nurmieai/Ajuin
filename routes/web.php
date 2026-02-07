<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\Dashboard;
use App\Livewire\Teacher\Activation;
use App\Livewire\Partners\Index;
use App\Livewire\Partners\Form;
use App\Livewire\Partners\Detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


Route::middleware('guest')->group(function () {
    Route::get('/register', \App\Livewire\Auth\Register::class)->name('register');
    Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
    Route::get('/', \App\Livewire\Auth\Login::class)->name('login');
});;
Route::middleware('auth')->group(function () {
    Volt::route('/dashboard', Dashboard::class)->name('dashboard');

    Route::middleware(['auth', 'role:teacher'])->group(function () {
        Volt::route('/activation', Activation::class)->name('activation');
    });

    Route::get('/partners', Index::class)->name('partners.index');
    Route::get('/partners/create', Form::class)->name('partners.create');
    Route::get('/partners/{partnerId}/edit', Form::class)->name('partners.edit');
    // Route::get('/partners/{partner}', Detail::class)->name('partners.show');
});


Route::get('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->middleware('auth')->name('logout');
