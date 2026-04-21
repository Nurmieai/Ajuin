<?php

namespace App\Livewire\Partners;

use Livewire\Component;
use App\Models\Partner;
use Livewire\Attributes\On;

class Detail extends Component
{
    public ?Partner $partner = null;
    public $latestReviews = [];
    public $averageRating = 0;

    #[On('showDetail')]
    public function loadPartner($id)
    {
        $this->partner = Partner::with(['majors'])->find($id);

        if ($this->partner) {
            // Load 3 review terbaru beserta data siswa
            $this->latestReviews = $this->partner->reviews()
                ->with('student')
                ->latest()
                ->take(3)
                ->get();

            // Hitung rata-rata
            $this->averageRating = $this->partner->reviews()->avg('rating') ?? 0;

            $this->dispatch('open-detail-modal');
        }
    }

    public function resetData()
    {
        $this->partner = null;
        $this->latestReviews = [];
        $this->averageRating = 0;
    }

    public function render()
    {
        return view('livewire.Partners.detail');
    }
}
