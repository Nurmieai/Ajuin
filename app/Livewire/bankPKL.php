<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Submission;

class BankPKL extends Component
{
    public function render()
    {
        $submissions = Submission::with('user')
            ->where('status', 'approved')
            ->latest()
            ->get();

        return view('livewire.bankpkl', [
            'submissions' => $submissions
        ]);
    }
}
