<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Submission;
use Livewire\Attributes\Url; // Tambahkan ini agar search tersimpan di URL

class BankPKL extends Component
{
    #[Url(history: true)]
    public $search = '';

    public function render()
    {
        $user = auth()->user();

        $submissions = Submission::with('user')
            ->where('status', 'approved')
            // Filter berdasarkan Role
            ->when($user->hasRole('student'), function ($query) use ($user) {
                return $query->where('user_id', $user->id);
            })
            // Filter berdasarkan Search
            ->when($this->search !== '', function ($query) {
                $query->where(function ($q) {
                    $q->where('company_name', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', function ($subQuery) {
                            $subQuery->where('fullname', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->latest()
            ->get();

        return view('livewire.bankpkl', [
            'submissions' => $submissions
        ]);
    }
}
