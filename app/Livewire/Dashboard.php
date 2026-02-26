<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Submission;
use App\Models\User;
use App\Models\Partner;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    // Semua property harus dideklarasikan di sini!
    public $submissions = [];
    public $totalActiveStudents = 0;
    public $totalSubmissions = 0;
    public $totalPartners = 0;
    public $availablePartners = 0;  // <-- PASTIKAN ADA INI!
    public $mySubmissions = 0;      // <-- DAN INI!

    public function mount()
    {
        $user = Auth::user();

        if ($user->hasRole('teacher')) {
            $this->submissions = Submission::with('user.major')->latest()->take(5)->get();
            $this->totalActiveStudents = User::where('is_active', true)
                ->whereHas('roles', fn($q) => $q->where('name', 'student'))
                ->count();
            $this->totalSubmissions = Submission::count();
            $this->totalPartners = Partner::count();
            
        } elseif ($user->hasRole('student')) {
            $this->submissions = Submission::with('user.major')
                ->where('user_id', $user->id)
                ->latest()
                ->get();
            
            // INISIALISASI UNTUK SISWA!
            $this->availablePartners = Partner::count();
            $this->mySubmissions = Submission::where('user_id', $user->id)->count();
        }
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}