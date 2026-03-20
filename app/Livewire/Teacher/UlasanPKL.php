<?php

namespace App\Livewire\Teacher;

use App\Models\Ulasan;
use Livewire\Component;
use Livewire\WithPagination;

class UlasanPKL extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortRating = '';
    public ?int $filterRating = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterRating(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $direction): void
    {
        $this->sortRating = $direction;
        $this->resetPage();
    }

    public function resetFilter(): void
    {
        $this->sortRating   = '';
        $this->filterRating = null;
        $this->resetPage();
    }

    public function render()
    {
        $query = Ulasan::with(['student', 'submission'])
            ->when($this->search, function ($q) {
                $q->where(function ($q2) {
                    $q2->where('judul', 'like', '%' . $this->search . '%')
                       ->orWhere('isi', 'like', '%' . $this->search . '%')
                       ->orWhereHas('student', fn($s) => $s->where('name', 'like', '%' . $this->search . '%'))
                       ->orWhereHas('submission', fn($s) => $s->where('company_name', 'like', '%' . $this->search . '%'));
                });
            })
            ->when($this->filterRating, fn($q) => $q->where('rating', $this->filterRating))
            ->when($this->sortRating, fn($q) => $q->orderBy('rating', $this->sortRating))
            ->latest();

        $ulasans    = $query->paginate(10);
        $totalCount = Ulasan::count();
        $avgRating  = Ulasan::avg('rating');

        return view('livewire.teacher.ulasan-p-k-l', [
            'ulasans'    => $ulasans,
            'totalCount' => $totalCount,
            'avgRating'  => round($avgRating, 1),
        ]);
    }
}