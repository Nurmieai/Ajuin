<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Dashboard;
use App\Livewire\Teacher\Activation;
use App\Livewire\Partners\Index;
use App\Livewire\Partners\Form;
use App\Livewire\Partners\Detail;
use App\Livewire\Student\Submission\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
    Route::get('/register', \App\Livewire\Auth\Register::class)->name('register');
    Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
    Route::get('/', \App\Livewire\Auth\Login::class)->name('login');
});;
Route::middleware('auth')->group(function () {
    Volt::route('/dashboard', Dashboard::class)->name('dashboard');
    Route::post('/password-update', function () {})->name('password-update');

    Route::middleware(['auth', 'role:teacher'])->group(function () {
        Volt::route('/activation', Activation::class)->name('activation');
    });

    Route::middleware(['auth', 'role:student'])->group(function () {
        Route::prefix('student')->as('student.')->group(function () {
            Volt::route('/', Create::class)->name('submission-create');
        });
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
