<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class Dashboard extends Component
{
    public $submissions = [];

    public function mount()
    {
        $user = Auth::user();

        if ($user->hasRole('teacher')) {

            $this->submissions = Submission::with('user')
                ->latest()
                ->take(5)
                ->get();
        } elseif ($user->hasRole('student')) {

            // Student → hanya miliknya sendiri
            $this->submissions = Submission::with('user')
                ->where('user_id', $user->id)
                ->latest()
                ->get();
        }
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
