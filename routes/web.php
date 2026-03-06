<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\BankPKL;
use App\Livewire\Dashboard;
use App\Livewire\Teacher\Activation;
use App\Livewire\Partners\Index;
use App\Livewire\Partners\Form;
use App\Livewire\Partners\Detail;
use App\Livewire\Student\Submission\Create;
use App\Livewire\Teacher\Submission\Detail as SubmissionDetail;
use App\Livewire\Teacher\Submission\Index as SubmissionIndex;
use App\Livewire\Teacher\Submission\SubmissionLetter as TeacherSubmissionLetter;
use App\Livewire\Teacher\Submission\SubmissionLetterDetail as TeacherSubmissionLetterDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Livewire\AcademicService as AcademicServiceIndex;
use App\Livewire\Student\AcademicService\Index as StudentAcademicServiceIndex;
use App\Livewire\Student\AcademicService\Submission\Index as AcademicServiceSubmissionIndex;
use App\Livewire\Student\AcademicService\Submission\Update;
use App\Livewire\Student\AcademicService\SubmissionLetter;
use Carbon\Exceptions\BadFluentConstructorException;
use App\Livewire\Student\AcademicService\UlasanPKL;
use App\Livewire\Student\Profile;
use App\Livewire\Teacher\StudentManage;
use App\Http\Controllers\SubmissionLetterController;

Route::middleware('guest')->group(function () {
    Volt::route('/forgot-password', ForgotPassword::class)->name('password.request');
    Volt::route('/reset-password/{token}', ResetPassword::class)->name('password.reset');
    Volt::route('/register', \App\Livewire\Auth\Register::class)->name('register');
    Volt::route('/login', \App\Livewire\Auth\Login::class)->name('login');
    Volt::route('/', \App\Livewire\Auth\Login::class)->name('home');
});;
Route::middleware('auth')->group(function () {
    Volt::route('/dashboard', Dashboard::class)->name('dashboard');
    Route::post('/password-update', function () {})->name('password-update');
    Volt::route('/bankPKL', BankPKL::class)->name('bankPKL');

    Route::middleware(['auth', 'role:teacher'])->group(function () {
        Route::prefix('teacher')->as('teacher.')->group(function () {
            Volt::route('/activation', StudentManage::class)->name('activation');
            Volt::route('/activation/students-manage', StudentManage::class)->name('students-manage');
            Volt::route('/submission', SubmissionIndex::class)->name('submission-manage');
            Volt::route('/submission-letter', TeacherSubmissionLetter::class)->name('submission-letter');
            Volt::route('/submission-letter/detail/{id}', TeacherSubmissionLetterDetail::class)->name('submission-letter-detail');
            Volt::route('/submission/detail/{id}', SubmissionDetail::class)->name('submission-detail');
            Route::get('/submission-letter/{id}/download', [SubmissionLetterController::class, 'download'])->name('submission-letter-download');
        });
    });

    Route::middleware(['auth', 'role:student'])->group(function () {
        Route::prefix('student')->as('student.')->group(function () {
            Volt::route('/', Create::class)->name('submission-create');
            Volt::route('/submission-manage', AcademicServiceSubmissionIndex::class)->name('submission-manage');
            Volt::route('/submission-letter/{submission}', SubmissionLetter::class)->name('submission-letter');
            Volt::route('/update/{id}', Update::class)->name('submission-edit');
            Volt::route('/ulasan-pkl', UlasanPKL::class)->name('ulasan-pkl');
            Volt::route('/academic-service', StudentAcademicServiceIndex::class)->name('academic-service');
            Volt::route('/profile', Profile::class)->name('profile');
        });
    });

    Volt::route('/partners', Index::class)->name('partners.index');
    Volt::route('/partners/create', Form::class)->name('partners.create');
    Volt::route('/partners/{partnerId}/edit', Form::class)->name('partners.edit');
    // Route::get('/partners/{partner}', Detail::class)->name('partners.show');
});


Route::get('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->middleware('auth')->name('logout');
